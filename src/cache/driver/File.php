<?php

namespace mon\store\cache\driver;

use mon\store\cache\Driver;
use InvalidArgumentException;
use mon\util\File as UtilFile;

/**
 * 文件缓存驱动
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class File extends Driver
{
    /**
     * 配置信息
     *
     * @var array
     */
    protected $config = [
        // 有效时间
        'expire'        => 0,
        // 使用子目录保存
        'cache_subdir'  => true,
        // 缓存前缀
        'prefix'        => '',
        // 缓存路径
        'path'          => '',
        // 数据压缩
        'data_compress' => false,
    ];

    /**
     * 构造方法
     *
     * @param array $config 配置信息
     */
    public function __construct(array $config = [])
    {
        if (!isset($config['path']) || empty($config['path'])) {
            throw new InvalidArgumentException("config required path");
        }
        // 定义配置
        $this->config = array_merge($this->config, $config);
        if (substr($this->config['path'], -1) != DIRECTORY_SEPARATOR) {
            $this->config['path'] .= DIRECTORY_SEPARATOR;
        }
        // 初始化
        $this->handler = UtilFile::instance();
        $this->init();
    }

    /**
     * 返回句柄对象，可执行其它高级方法
     *
     * @return UtilFile
     */
    public function handler()
    {
        return $this->handler;
    }

    /**
     * 初始化
     *
     * @return void
     */
    public function init()
    {
        // 创建项目缓存目录
        return $this->handler()->createDir($this->config['path']);
    }

    /**
     * 获取缓存内容
     *
     * @param  string $name    名称
     * @param  mixed  $default 默认值
     * @return mixed
     */
    public function get($name, $default = null)
    {
        $filename = $this->getCacheKey($name);
        if (!is_file($filename)) {
            return $default;
        }
        $content = $this->handler()->read($filename);
        if (false !== $content) {
            $expire = (int) substr($content, 8, 12);
            if (0 != $expire && $_SERVER['REQUEST_TIME'] > filemtime($filename) + $expire) {
                //缓存过期删除缓存文件
                $this->handler()->removeFile($filename);
                return $default;
            }
            $content = substr($content, 20, -3);
            if ($this->config['data_compress'] && function_exists('gzcompress')) {
                //启用数据压缩
                $content = gzuncompress($content);
            }

            $content = unserialize($content);
            return $content;
        } else {
            return $default;
        }
    }

    /**
     * 写入缓存
     *
     * @param string    $name    缓存变量名
     * @param mixed     $value   存储数据
     * @param integer   $expire  有效时间 0为永久
     * @return boolean
     */
    public function set($name, $value, $expire = null)
    {
        if (is_null($expire)) {
            $expire = $this->config['expire'];
        }
        $filename = $this->getCacheKey($name);
        $data = serialize($value);
        if ($this->config['data_compress'] && function_exists('gzcompress')) {
            //数据压缩
            $data = gzcompress($data, 3);
        }
        $data   = "<?php\n//" . sprintf('%012d', $expire) . $data . "\n?>";
        $result = $this->handler()->createFile($data, $filename, false);
        if ($result) {
            clearstatcache();
            return true;
        } else {
            return false;
        }
    }

    /**
     * 缓存自增
     *
     * @param string $name 缓存变量名
     * @param integer $step 自增步长
     * @return boolean
     */
    public function inc($name, $step = 1)
    {
        $value = $this->has($name) ? ($this->get($name) + $step) : $step;
        return $this->set($name, $value);
    }

    /**
     * 缓存自减
     *
     * @param string $name 缓存变量名
     * @param integer $step 自减步长
     * @return boolean
     */
    public function dec($name, $step = 1)
    {
        $value = $this->has($name) ? ($this->get($name) - $step) : -$step;
        return $this->set($name, $value);
    }

    /**
     * 是否存在缓存
     *
     * @param  string  $name 名称
     * @return boolean       [description]
     */
    public function has($name)
    {
        return $this->get($name) ? true : false;
    }

    /**
     * 删除缓存
     *
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function remove($name)
    {
        return $this->handler()->removeFile($this->getCacheKey($name));
    }

    /**
     * 清除缓存
     *
     * @return boolean
     */
    public function clear()
    {
        $files = (array) glob($this->config['path'] . ($this->config['prefix'] ? $this->config['prefix'] . DIRECTORY_SEPARATOR : '') . '*');
        foreach ($files as $path) {
            if (is_dir($path)) {
                array_map('unlink', glob($path . '/*.php'));
                rmdir($path);
            } else {
                unlink($path);
            }
        }
        return true;
    }

    /**
     * 取得变量的存储文件名
     *
     * @param  string $key 名称
     * @return string
     */
    protected function getCacheKey($name)
    {
        $name = md5($name);
        if ($this->config['cache_subdir']) {
            // 使用子目录
            $name = substr($name, 0, 2) . DIRECTORY_SEPARATOR . substr($name, 2);
        }
        if ($this->config['prefix']) {
            $name = $this->config['prefix'] . DIRECTORY_SEPARATOR . $name;
        }
        $filename = $this->config['path'] . $name . '.php';
        $dir = dirname($filename);
        if (!is_dir($dir)) {
            $this->handler()->createDir($dir);
        }

        return $filename;
    }
}
