<?php

namespace mon\store\cache;

/**
 * 缓存驱动类
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
abstract class Driver
{
    /**
     * 驱动实例
     * 
     * @var mixed
     */
    protected $handler;

    /**
     * 获取缓存内容
     *
     * @param  string $name    名称
     * @param  mixed  $default 默认值
     * @return mixed
     */
    abstract public function get($name, $default = null);

    /**
     * 写入缓存
     *
     * @param string  $name    缓存变量名
     * @param mixed   $value   存储数据
     * @param integer $expire  有效时间 0为永久
     * @return boolean
     */
    abstract public function set($name, $value, $expire = null);

    /**
     * 自增缓存（针对数值缓存）
     *
     * @param  string    $name 缓存变量名
     * @param  integer   $step 步长
     * @return boolean
     */
    abstract public function inc($name, $step = 1);

    /**
     * 自减缓存（针对数值缓存）
     *
     * @param  string    $name 缓存变量名
     * @param  integer   $step 步长
     * @return boolean
     */
    abstract public function dec($name, $step = 1);

    /**
     * 是否存在缓存
     *
     * @param  string  $name 名称
     * @return boolean
     */
    abstract public function has($name);

    /**
     * 删除缓存
     *
     * @param string $name 缓存变量名
     * @return boolean
     */
    abstract public function remove($name);

    /**
     * 清除缓存
     *
     * @return boolean
     */
    abstract public function clear();

    /**
     * 返回驱动实例，可执行其它高级方法
     *
     * @return mixed
     */
    public function handler()
    {
        return $this->handler;
    }

    /**
     * 读取缓存并删除
     *
     * @param  string $name 缓存变量名
     * @return mixed
     */
    public function pull($name)
    {
        $result = $this->get($name, false);

        if ($result) {
            $this->remove($name);
            return $result;
        }
        return null;
    }

    /**
     * 获取实际的缓存标识
     *
     * @param  string $name 缓存名
     * @return string
     */
    protected function getCacheKey($name)
    {
        return $this->config['prefix'] . $name;
    }
}
