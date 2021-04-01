<?php

namespace mon\store\cache\driver;

use mon\store\Rdb;
use mon\store\cache\Driver;

/**
 * Redis缓存驱动
 */
class Redis extends Driver
{
    /**
     * 配置信息
     *
     * @var array
     */
    protected $config = [
        // 链接host
        'host'      => '127.0.0.1',
        // 链接端口
        'port'      => 6379,
        // 链接密码
        'password'  => '',
        // 自定义键前缀
        'prefix'    => '',
        // 读取超时时间
        'timeout'   => 0,
        // 缓存有效时间
        'expire'    => 0,
    ];

    /**
     * 构造方法
     *
     * @param array $config 配置信息
     */
    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
        $this->handler = new Rdb($this->config);
    }

    /**
     * 返回句柄对象，可执行其它高级方法
     *
     * @return Rdb
     */
    public function handler()
    {
        return $this->handler;
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
        $value = $this->handler()->get($this->getCacheKey($name));
        if (is_null($value) || false === $value) {
            return $default;
        }

        return unserialize($value);
    }

    /**
     * 写入缓存
     *
     * @param string  $name    缓存变量名
     * @param mixed   $value   存储数据
     * @param integer $expire  有效时间 0为永久
     * @return boolean
     */
    public function set($name, $value, $expire = null)
    {
        if (is_null($expire)) {
            $expire = $this->config['expire'];
        }

        $key = $this->getCacheKey($name);
        $value = serialize($value);

        if ($expire) {
            $result = $this->handler()->setex($key, $value, $expire);
        } else {
            $result = $this->handler()->set($key, $value);
        }

        return $result;
    }

    /**
     * 自增缓存（针对数值缓存）
     *
     * @param  string    $name 缓存变量名
     * @param  integer   $step 步长
     * @return boolean
     */
    public function inc($name, $step = 1)
    {
        $key = $this->getCacheKey($name);
        return $this->handler()->incrBy($key, $step);
    }

    /**
     * 自减缓存（针对数值缓存）
     *
     * @param  string    $name 缓存变量名
     * @param  integer   $step 步长
     * @return boolean
     */
    public function dec($name, $step = 1)
    {
        $key = $this->getCacheKey($name);
        return $this->handler()->decrBy($key, $step);
    }

    /**
     * 是否存在缓存
     *
     * @param  string  $name 名称
     * @return boolean
     */
    public function has($name)
    {
        return $this->handler()->exists($this->getCacheKey($name)) ? true : false;
    }

    /**
     * 删除缓存
     *
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function remove($name)
    {
        return $this->handler()->delete($this->getCacheKey($name)) ? true : false;
    }

    /**
     * 清除缓存
     *
     * @return boolean
     */
    public function clear()
    {
        return $this->handler->flushDB();
    }
}
