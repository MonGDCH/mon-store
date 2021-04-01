<?php

namespace mon\store;

use mon\util\Instance;
use mon\store\cache\Driver;

/**
 * 缓存类
 *
 * @method Driver get(string $name, mixed $default = null) 获取缓存
 * @method Driver set(string $name, mixed $value, integer $expire = null) 设置缓存
 * @method Driver inc(string $name, integer $step = 1) 缓存自增
 * @method Driver dec(string $name, integer $step = 1) 缓存自减
 * @method Driver has(string $name) 是否存在缓存
 * @method Driver remove(string $name) 删除缓存
 * @method Driver clear() 清空缓存
 * @method Driver pull(string $name) 读取缓存并删除
 * @method Driver handler() 获取驱动
 * 
 * @author Mon <985558837@qq.com>
 * @version 2.0.0 2021-04-01 驱动模式，支持File、Redis缓存类型
 */
class Cache
{
    use Instance;

    /**
     * 配置信息
     *
     * @var array
     */
    protected $config = [];

    /**
     * 缓存驱动
     *
     * @var Driver
     */
    protected $driver = null;

    /**
     * 后缀方法
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = $config;
    }

    /**
     * 获取配置信息
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * 设置配置信息
     *
     * @param array $config
     * @return Cache
     */
    public function setConfig(array $config)
    {
        $this->config = array_merge($this->config, $config);
        return $this;
    }

    /**
     * 初始化链接驱动
     *
     * @return Driver
     */
    public function connect()
    {
        if (is_null($this->driver)) {
            // 缓存驱动类型，默认File
            $type = (isset($this->config['type']) && !empty($this->config['type'])) ? $this->config['type'] : 'File';
            $class = "\\mon\\store\\cache\\driver\\" . ucfirst($type);
            $this->driver = new $class($this->config);
        }

        return $this->driver;
    }

    /**
     * 调用缓存驱动方法
     *
     * @param string $method 调用方法
     * @param array $args 参数
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->connect(), $method], $args);
    }
}
