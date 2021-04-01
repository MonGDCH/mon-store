<?php

namespace mon\store;

use Redis;
use mon\util\Instance;
use BadFunctionCallException;
use InvalidArgumentException;

/**
 * Redis操作类
 *
 * @author Mon <985558837@qq.com>
 * @version 1.0.0 2018-05-20
 * @version 1.1.0 2019-12-02 修复自定义Redis配置无效的BUG
 * @version 1.2.0 2021-03-29 优化代码，增强注解
 */
class Rdb
{
    use Instance;

    /**
     * redis链接实例
     * 
     * @var Redis
     */
    private $handler;

    /**
     * 配置信息
     *
     * @var array
     */
    private $config = [
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
    ];

    /**
     * 构造函数
     *
     * @param array $config 配置信息
     */
    public function __construct($config = [])
    {
        if (!extension_loaded('redis')) {
            throw new BadFunctionCallException('not support: redis');
        }
        if (!empty($config)) {
            $this->config = array_merge($this->config, $config);
        }

        $this->handler = new Redis();
        $this->handler->connect($this->config['host'], $this->config['port']);
        if ($this->config['password']) {
            $this->handler->auth($this->config['password']);
        }
        if ($this->config['prefix']) {
            $this->handler->setOption(Redis::OPT_PREFIX, $this->config['prefix']);
        }
        if ($this->config['timeout']) {
            $this->handler->setOption(3, $this->config['timeout']);
        }
    }

    /**
     * 执行原生的redis操作
     *
     * @return Redis
     */
    public function getRedis()
    {
        return $this->handler;
    }

    /************* string类型操作命令 *****************/

    /**
     * 获取key值
     *
     * @param  string $key 键名
     * @return string|false
     */
    public function get($key)
    {
        return $this->handler->get($key);
    }

    /**
     * 获取多个key值
     *
     * @param  array  $key 键名
     * @return array
     */
    public function mGet(array $key)
    {
        return $this->handler->mGet($key);
    }

    /**
     * 返回字符串的一部分
     *
     * @param  string $key   key名
     * @param  integer $start 起始点
     * @param  integer $end   结束点
     * @return string|false
     */
    public function getRange($key, $start, $end)
    {
        return $this->handler->getRange($key, $start, $end);
    }

    /**
     * 返回字符串长度
     *
     * @param  string $key 键名
     * @return integer
     */
    public function strlen($key)
    {
        return $this->handler->strlen($key);
    }

    /**
     * 获取key的原值，并设置新值，不存在原值则返回false
     *
     * @param  string $key   键名
     * @param  string $value 键值
     * @return string
     */
    public function getSet($key, $value)
    {
        return $this->handler->getSet($key, $value);
    }

    /**
     * 设置一个key值
     *
     * @param string $key     键名
     * @param string $value   键名
     * @param integer $timeout 设置超时时间
     * @return boolean
     */
    public function set($key, $value, $timeout = null)
    {
        return $this->handler->set($key, $value, $timeout);
    }

    /**
     * 添加指定的字符串到指定的字符串key
     *
     * @param  string $key   键名
     * @param  string $value 键值
     * @return integer 返回新的key size
     */
    public function append($key, $value)
    {
        return $this->handler->append($key, $value);
    }

    /**
     * 设置一个有过期时间的key值, 单位秒
     * 
     * @param  string $key    键名
     * @param  string $value  键值
     * @param  integer $expire 有效时间, 单位秒
     * @return boolean
     */
    public function setex($key, $value, $expire)
    {
        return $this->handler->setex($key, $expire, $value);
    }

    /**
     * 设置一个有过期时间的key值, 单位毫秒
     * 
     * @param  string $key    键
     * @param  string $value  值
     * @param  integer $expire 有效时间，单位毫秒
     * @return boolean
     */
    public function psetex($key, $value, $expire)
    {
        return $this->handler->psetex($key, $expire, $value);
    }

    /**
     * 设置一个key值,如果key存在,不做任何操作
     * 
     * @param  string $key   键
     * @param  string $value 值
     * @return boolean
     */
    public function setnx($key, $value)
    {
        return $this->handler->setnx($key, $value);
    }

    /**
     * 替换字符串的一部分, 主要配置setex, 实现更新值有效时间不更新
     *
     * @param string  $key    key
     * @param string  $value  值
     * @param integer $offset 偏移值
     * @return string 修改后的字符串长度
     */
    public function setRange($key, $value, $offset = 0)
    {
        return $this->handler->setRange($key, $offset, $value);
    }

    /**
     * 批量设置key值
     * 
     * @param  array $array 键值对
     * @return boolean
     */
    public function mSet($array)
    {
        return $this->handler->mSet($array);
    }

    /**
     * 移除已经存在key
     *
     * @param  string $key key名，字符串或者数组
     * @return integer 返回删除KEY-VALUE的数量
     */
    public function delete($key)
    {
        return $this->handler->delete($key);
    }

    /**
     * 判断一个key值是不是存在
     *
     * @param string $key 键名
     * @return boolean
     */
    public function exists($key)
    {
        return $this->handler->exists($key);
    }

    /**
     * 对key的值加value, 相当于 key = key + value
     *
     * @param  string  $key   键
     * @param  integer $value 值
     * @return integer 返回新的INT数值
     */
    public function incrBy($key, $value)
    {
        return $this->handler->incrBy($key, $value);
    }

    /**
     * 对key的值加value, 相当于 key = key + value
     *
     * @param  string $key   键
     * @param  float  $value 值
     * @return float
     */
    public function incrByFloat($key, $value)
    {
        return $this->handler->incrByFloat($key, $value);
    }

    /**
     * 对key的值减value, 相当于 key = key - value
     *
     * @param  string  $key   键
     * @param  integer $value 值
     * @return integer 返回新的INT数值
     */
    public function decrBy($key, $value)
    {
        return $this->handler->decrBy($key, $value);
    }

    /**
     * 对key的值减value, 相当于 key = key - value
     *
     * @param  string $key   键
     * @param  float  $value 值
     * @return float 返回新的INT数值
     */
    public function decrByFloat($key, $value)
    {
        return $this->handler->decrByFloat($key, $value);
    }

    /***************** hash类型操作函数 *******************/

    /**
     * 为hash表设定一个字段的值
     * 
     * @param  string $key   键
     * @param  string $field 字段
     * @param  string $value 值
     * @return string|false
     */
    public function hSet($key, $field, $value)
    {
        return $this->handler->hSet($key, $field, $value);
    }

    /**
     * 得到hash表中一个字段的值
     * 
     * @param  string $key   键
     * @param  string $field 字段
     * @return string|false
     */
    public function hGet($key, $field)
    {
        return $this->handler->hGet($key, $field);
    }

    /**
     * 删除hash表中指定字段 ,支持批量删除
     *
     * @param  string $key   键
     * @param  string $field 字段
     * @return boolean
     */
    public function hDel($key, $field)
    {
        $delNum = 0;
        if (is_array($field)) {
            // 字符串，批量删除
            foreach ($field as $row) {
                $delNum += $this->handler->hDel($key, $row);
            }
        } else {
            // 字符串，删除单个
            $delNum += $this->handler->hDel($key, $field);
        }

        return $delNum;
    }

    /**
     * 返回hash表元素个数
     *
     * @param  string $key 键
     * @return integer|false
     */
    public function hLen($key)
    {
        return $this->handler->hLen($key);
    }

    /**
     * 为hash表设定一个字段的值,如果字段存在，返回false
     *
     * @param  string $key   键
     * @param  string $field 字段
     * @param  string $value 值
     * @return boolean
     */
    public function hSetNx($key, $field, $value)
    {
        return $this->handler->hSetNx($key, $field, $value);
    }

    /**
     * 为hash表多个字段设定值。
     * 
     * @param  string $key   键
     * @param  array $value 值
     * @return boolean
     */
    public function hMset($key, array $value)
    {
        if (!is_array($value)) {
            return false;
        }

        return $this->handler->hMset($key, $value);
    }

    /**
     * 获取hash表多个字段值。
     *
     * @param string $key  键
     * @param array|string $value 值，string则以','号分隔字段
     * @return array|bool
     */
    public function hMget($key, $field)
    {
        if (!is_array($field)) {
            $field = explode(',', $field);
        }

        return $this->handler->hMget($key, $field);
    }

    /**
     * 为hash表的某个值累加整数，可以负数
     * 
     * @param string $key    key值
     * @param string $field  字段
     * @param integer $value 步长
     * @return integer
     */
    public function hIncrBy($key, $field, $value)
    {
        $value = intval($value);

        return $this->handler->hIncrBy($key, $field, $value);
    }

    /**
     * 为hash表的某个值累加浮点数，可以负数
     * 
     * @param string $key   key值
     * @param string $field 字段
     * @param float  $value 步长
     * @return float
     */
    public function hIncrByFloat($key, $field, $value)
    {
        $value = floatval($value);

        return $this->handler->hIncrByFloat($key, $field, $value);
    }

    /**
     * 返回所有hash表的所有字段
     *
     * @param string $key 键
     * @return array
     */
    public function hKeys($key)
    {
        return $this->handler->hKeys($key);
    }

    /**
     * 返回所有hash表的字段值，为一个索引数组
     * 
     * @param string $key 键
     * @return array
     */
    public function hVals($key)
    {
        return $this->handler->hVals($key);
    }

    /**
     * 验证HASH表中是否存在指定的KEY-VALUE
     *
     * @param  string $key   键
     * @param  string $value 值
     * @return boolean
     */
    public function hExists($key, $value)
    {
        return $this->handler->hExists($key, $value);
    }

    /**
     * 返回所有hash表的字段值，为一个关联数组
     * 
     * @param string $key 键
     * @return array
     */
    public function hGetAll($key)
    {
        return $this->handler->hGetAll($key);
    }

    /********************* List队列类型操作命令 ************************/

    /**
     * 在队列尾部插入一个元素
     * 
     * @param string $key 键
     * @param string $value 值
     * @return integer|false 返回队列长度
     */
    public function rPush($key, $value)
    {
        return $this->handler->rPush($key, $value);
    }

    /**
     * 在队列尾部插入一个元素 如果key不存在，什么也不做
     * 
     * @param string $key 键
     * @param string $value 值
     * @return integer|false 返回队列长度
     */
    public function rPushx($key, $value)
    {
        return $this->handler->rPushx($key, $value);
    }

    /**
     * 在队列头部插入一个元素
     * 
     * @param string $key 键
     * @param string $value 值
     * @return integer|false 返回队列长度
     */
    public function lPush($key, $value)
    {
        return $this->handler->lPush($key, $value);
    }

    /**
     * 在队列头插入一个元素 如果key不存在，什么也不做
     *
     * @param string $key 键
     * @param string $value 值
     * @return integer|false 返回队列长度
     */
    public function lPushx($key, $value)
    {
        return $this->handler->lPushx($key, $value);
    }

    /**
     * 返回队列长度
     * 
     * @param string $key 值
     * @return integer
     */
    public function lLen($key)
    {
        return $this->handler->lLen($key);
    }

    /**
     * 返回队列大小
     *
     * @param  string $key 键
     * @return integer|false
     */
    public function lSize($key)
    {
        return $this->handler->lSize($key);
    }

    /**
     * 返回队列指定区间的元素
     * 
     * @param string $key    键
     * @param integer $start 起点
     * @param integer $end   终点
     * @return array
     */
    public function lRange($key, $start, $end)
    {
        return $this->handler->lrange($key, $start, $end);
    }

    /**
     * 截取LIST中指定范围内的元素组成一个新的LIST并指向KEY
     *
     * @param  string $key    键
     * @param  integer $start 起点
     * @param  integer $end   终点
     * @return array
     */
    public function lTrim($key, $start, $end)
    {
        return $this->handler->lTrim($key, $start, $end);
    }

    /**
     * 返回队列中指定索引的元素
     * 
     * @param string $key   键
     * @param string $index 值
     * @return mixed
     */
    public function lIndex($key, $index)
    {
        return $this->handler->lIndex($key, $index);
    }

    /**
     * 根据索引值返回指定KEY-LIST中的元素，0为第一个
     *
     * @param  string  $key   键
     * @param  integer $index 值
     * @return string|false
     */
    public function lGet($key, $index)
    {
        return $this->handler->lGet($key, $index);
    }

    /**
     * 设定队列中指定index的值。
     * 
     * @param string  $key   键
     * @param integer $index 索引
     * @param string  $value 值
     * @return boolean
     */
    public function lSet($key, $index, $value)
    {
        return $this->handler->lSet($key, $index, $value);
    }

    /**
     * 删除值为vaule的count个元素
     * PHP-redis扩展的数据顺序与命令的顺序不太一样，不知道是不是bug
     * count>0 从尾部开始
     *  >0　从头部开始
     *  =0　删除全部
     *  
     * @param string $key   键
     * @param integer $count 数量
     * @param string $value 值
     * @return integer|false
     */
    public function lRem($key, $count, $value)
    {
        return $this->handler->lRem($key, $value, $count);
    }

    /**
     * 删除并返回队列中的头元素。
     * 
     * @param string $key   键
     * @return string|false
     */
    public function lPop($key)
    {
        return $this->handler->lPop($key);
    }

    /**
     * 删除并返回队列中的尾元素
     * 
     * @param string $key   键
     * @return string|false
     */
    public function rPop($key)
    {
        return $this->handler->rPop($key);
    }

    /**
     * 从key-LIST的最后弹出一个元素，并且把这个元素从target-LIST的顶部压入target-LIST中
     *
     * @param  string $key        键
     * @param  string $target_key 目标键
     * @return string|false
     */
    public function rpoplpush($key, $target_key)
    {
        return $this->handler->rpoplpush($key, $target_key);
    }

    /************* 无序集合操作命令 *****************/

    /**
     * 返回集合中所有元素
     *
     * @param string $key   键
     * @return array
     */
    public function sMembers($key)
    {
        return $this->handler->sMembers($key);
    }

    /**
     * 检查VALUE是否是key-SET容器中的成员
     *
     * @param  string $key   键
     * @param  string $value 值
     * @return boolean
     */
    public function sIsMember($key, $value)
    {
        return $this->handler->sIsMember($key, $value);
    }

    /**
     * 添加集合
     *
     * @param string $key   键
     * @param string $value 值
     * @return boolean
     */
    public function sAdd($key, $value)
    {
        return $this->handler->sAdd($key, $value);
    }

    /**
     * 返回无序集合的元素个数
     *
     * @param string $key   键
     * @return integer
     */
    public function sCard($key)
    {
        return $this->handler->sCard($key);
    }

    /**
     * 随机返回一个元素，并且在key-SET容器中移除该元素。
     *
     * @param  string $key 键
     * @return string|false
     */
    public function sPop($key)
    {
        return $this->handler->sPop($key);
    }

    /**
     * 取得指定key-SET容器中的一个随机元素，但不会在key-SET容器中移除它
     *
     * @param  string $key 键
     * @return string|false
     */
    public function sRandMember($key)
    {
        return $this->handler->sRandMember($key);
    }

    /**
     * 从集合中删除一个元素
     *
     * @param string $key   键
     * @param string $value 值
     * @return boolean
     */
    public function sRem($key, $value)
    {
        return $this->handler->sRem($key, $value);
    }

    /**
     * 移动一个指定的MEMBER从key-SET到指定的target-SET中
     *
     * @param  string $key    键
     * @param  string $target 目标键
     * @param  string $member 成员值
     * @return string|false
     */
    public function sMove($key, $target, $member)
    {
        return $this->handler->sMove($key, $target, $member);
    }

    /**
     * 返回指定两个SETS集合的交集结果，注意：原生的sInter可传N个key名
     *
     * @param  string $key1 键1
     * @param  string $key2 键2
     * @return array
     */
    public function sInter($key1, $key2)
    {
        return $this->handler->sInter($key1, $key2);
    }

    /**
     * 执行一个交集操作，并把结果存储到一个新的SET容器中
     *
     * @param  string $name 新的key名
     * @param  string $key1 键1
     * @param  string $key2 键2
     * @return integer
     */
    public function sInterStore($name, $key1, $key2)
    {
        return $this->handler->sInterStore($name, $key1, $key2);
    }

    /**
     * 返回指定两个SETS集合的并集结果，注意：原生的sUnion可传N个key名
     *
     * @param  string $key1 键1
     * @param  string $key2 键2
     * @return array
     */
    public function sUnion($key1, $key2)
    {
        return $this->handler->sUnion($key1, $key2);
    }

    /**
     * 执行一个并集操作，并把结果存储到一个新的SET容器中
     *
     * @param  string $name 新的key名
     * @param  string $key1 键1
     * @param  string $key2 键2
     * @return integer 并集结果的个数
     */
    public function sUnionStore($name, $key1, $key2)
    {
        return $this->handler->sUnionStore($name, $key1, $key2);
    }

    /**
     * 求2个集合的差集
     *
     * @param string $key1  键1
     * @param string $key2  键2
     * @return array
     */
    public function sDiff($key1, $key2)
    {
        return $this->handler->sDiff($key1, $key2);
    }

    /**
     * 执行一个差集操作，并把结果存储到一个新的SET容器中
     *
     * @param  string $name 键名
     * @param  string $key1 键1
     * @param  string $key2 键2
     * @return integer 结果集的个数
     */
    public function sDiffStore($name, $key1, $key2)
    {
        return $this->handler->sDiffStore($name, $key1, $key2);
    }

    /**
     * 筛选集合
     *
     * @param  string  $key    键
     * @param  integer $option 其他信息
     * @return array
     */
    public function sort($key, $option = null)
    {
        return $this->handler->sort($key, $option);
    }

    /********************* sorted set有序集合类型操作命令 *********************/

    /**
     * 给当前集合添加一个元素，如果value已经存在，会更新order的值。
     * 
     * @param string $key   键
     * @param string $order 序号
     * @param string $value 值
     * @return boolean
     */
    public function zAdd($key, $order, $value)
    {
        return $this->handler->zAdd($key, $order, $value);
    }

    /**
     * 从有序集合中删除指定的成员
     *
     * @param  string $key   键
     * @param  string $value 值
     * @return boolean
     */
    public function zDelete($key, $value)
    {
        return $this->handler->zDelete($key, $value);
    }

    /**
     * 给$value成员的order值，增加$num,可以为负数
     * 
     * @param string $key   键
     * @param string $num   序号
     * @param string $value 值
     * @return mixed 返回新的order
     */
    public function zinCry($key, $num, $value)
    {
        return $this->handler->zinCry($key, $num, $value);
    }

    /**
     * 删除值为value的元素
     * 
     * @param string $key   键
     * @param string $value 值
     * @return boolean
     */
    public function zRem($key, $value)
    {
        return $this->handler->zRem($key, $value);
    }

    /**
     * 集合以order递增排列后，0表示第一个元素，-1表示最后一个元素
     * 
     * @param string  $key   键
     * @param integer $start 开始位置
     * @param integer $end   结束位置
     * @return array
     */
    public function zRange($key, $start, $end)
    {
        return $this->handler->zRange($key, $start, $end);
    }

    /**
     * 集合以order递减排列后，0表示第一个元素，-1表示最后一个元素
     * 
     * @param string  $key   键
     * @param integer $start 开始位置
     * @param integer $end   结束位置
     * @return array
     */
    public function zRevRange($key, $start, $end)
    {
        return $this->handler->zRevRange($key, $start, $end);
    }

    /**
     * 集合以order递增排列后，返回指定order之间的元素。
     * min和max可以是-inf和+inf　表示最大值，最小值
     * 
     * @param string  $key    键
     * @param integer $start  开始位置
     * @param integer $end    结束位置
     * @param array   $option 参数
     *     withscores=>true，表示数组下标为Order值，默认返回索引数组
     *     limit=>array(0,1) 表示从0开始，取一条记录。
     * @return array
     */
    public function zRangeByScore($key, $start = '-inf', $end = "+inf", $option = [])
    {
        return $this->handler->zRangeByScore($key, $start, $end, $option);
    }

    /**
     * 集合以order递减排列后，返回指定order之间的元素。
     * min和max可以是-inf和+inf　表示最大值，最小值
     * 
     * @param string  $key    键
     * @param integer $start  开始位置
     * @param integer $end    结束位置
     * @param array   $option 参数
     *     withscores=>true，表示数组下标为Order值，默认返回索引数组
     *     limit=>array(0,1) 表示从0开始，取一条记录。
     * @return array
     */
    public function zRevRangeByScore($key, $start = '-inf', $end = "+inf", $option = [])
    {
        return $this->handler->zRevRangeByScore($key, $start, $end, $option);
    }

    /**
     * 返回order值在start end之间的数量
     * 
     * @param string  $key   键
     * @param integer $start 开始位置
     * @param integer $end   结束位置
     * @return integer
     */
    public function zCount($key, $start, $end)
    {
        return $this->handler->zCount($key, $start, $end);
    }

    /**
     * 返回值为value的order值
     * 
     * @param string $key   键
     * @param float  $value 值
     * @return float
     */
    public function zScore($key, $value)
    {
        return $this->handler->zScore($key, $value);
    }

    /**
     * 返回集合以score递增加排序后，指定成员的排序号，从0开始。
     * 
     * @param string $key   键
     * @param float  $value 值
     * @return float
     */
    public function zRank($key, $value)
    {
        return $this->handler->zRank($key, $value);
    }

    /**
     * 返回集合以score递增加排序后，指定成员的排序号，从0开始。
     * 
     * @param string $key   键
     * @param float  $value 值
     * @return float
     */
    public function zRevRank($key, $value)
    {
        return $this->handler->zRevRank($key, $value);
    }

    /**
     * 删除集合中，score值在start end之间的元素　包括start end
     * min和max可以是-inf和+inf　表示最大值，最小值
     * 
     * @param string  $key   键
     * @param integer $start 开始位置
     * @param integer $end   结束位置
     * @return integer 删除成员的数量。
     */
    public function zRemRangeByScore($key, $start, $end)
    {
        return $this->handler->zRemRangeByScore($key, $start, $end);
    }

    /**
     * 删除集合中，score值在start end之间的元素　不包括start end
     *
     * @param string  $key   键
     * @param integer $start 开始位置
     * @param integer $end   结束位置
     * @return integer
     */
    public function zRemRangeByRank($key, $start, $end)
    {
        return $this->handler->zRemRangeByRank($key, $start, $end);
    }

    /**
     * 返回集合元素个数。
     * 
     * @param string $key   键
     * @return integer
     */
    public function zCard($key)
    {
        return $this->handler->zCard($key);
    }

    /**
     * 返回集合元素个数。
     *
     * @param  string $key 键
     * @return integer
     */
    public function zSize($key)
    {
        return $this->handler->zSize($key);
    }

    /**
     * 将key对应的有序集合中member元素的scroe加上increment
     * 如果指定的member不存在，那么将会添加该元素，并且其score的初始值为increment。
     * 如果key不存在，那么将会创建一个新的有序列表，其中包含member这一唯一的元素。
     * 如果key对应的值不是有序列表，那么将会发生错误。
     * 指定的score的值应该是能够转换为数字值的字符串，并且接收双精度浮点数。同时，你也可用提供一个负值，这样将减少score的值。
     *
     * @param  string $key    键
     * @param  float  $value  值
     * @param  string $member 成员
     * @return float
     */
    public function zIncrBy($key, $value, $member)
    {
        return $this->handler->zIncrBy($key, $value, $member);
    }

    /********************* 事务的相关方法 ************************/

    /**
     * 监控key,就是一个或多个key添加一个乐观锁
     * 在此期间如果key的值如果发生的改变，刚不能为key设定值
     * 可以重新取得Key的值。
     * 
     * @param string $key   键
     * @return mixed
     */
    public function watch($key)
    {
        return $this->handler->watch($key);
    }

    /**
     * 取消当前链接对所有key的watch
     *  EXEC 命令或 DISCARD 命令先被执行了的话，那么就不需要再执行 UNWATCH 了
     * 
     * @return mixed
     */
    public function unwatch()
    {
        return $this->handler->unwatch();
    }

    /**
     * 开启一个事务
     * 事务的调用有两种模式Redis::MULTI和Redis::PIPELINE，
     * 默认是Redis::MULTI模式，
     * Redis::PIPELINE管道模式速度更快，但没有任何保证原子性有可能造成数据的丢失
     *
     * @param integer $type  事务启动方式
     * @return mixed
     */
    public function multi($type = Redis::MULTI)
    {
        return $this->handler->multi($type);
    }

    /**
     * 执行一个事务
     * 收到 EXEC 命令后进入事务执行，事务中任意命令执行失败，其余的命令依然被执行
     * 
     * @return mixed
     */
    public function exec()
    {
        return $this->handler->exec();
    }

    /**
     * 回滚一个事务
     * 
     * @return mixed
     */
    public function discard()
    {
        return $this->handler->discard();
    }

    /************* 订阅操作命令 *****************/

    /**
     * 订阅频道
     *
     * @param  string $key      订阅的频道名，可字符串，可数组
     * @param  mixed  $callback 回调函数，function($redis, $chan, $msg){}
     * @return mixed
     */
    public function subscribe($key, $callback)
    {
        return $this->handler->subscribe($key, $callback);
    }

    /**
     * 发布订阅
     *
     * @param  string $channel 发布的频道
     * @param  string $messgae 发布信息
     * @return integer 订阅数
     */
    public function publish($channel, $messgae)
    {
        return $this->handler->publish($channel, $messgae);
    }


    /************* 管理操作命令 *****************/

    /**
     * 测试当前链接是不是已经失效，没有失效返回+PONG，失效返回false
     * 
     * @return string|false
     */
    public function ping()
    {
        return $this->handler->ping();
    }

    /**
     * 密码认证
     *
     * @param  string $auth 密码
     * @return boolean
     */
    public function auth($auth)
    {
        return $this->handler->auth($auth);
    }

    /**
     * 选择数据库
     *
     * @param integer $dbId 数据库ID号
     * @return boolean
     */
    public function select($dbId)
    {
        return $this->handler->select($dbId);
    }

    /**
     * 移动一个KEY-VALUE到另一个DB
     *
     * @param  string $key     key值
     * @param  integer $dbindex 要移动到的数据库ID
     * @return boolean
     */
    public function move($key, $dbindex)
    {
        return $this->handler->move($key, $dbindex);
    }

    /**
     * 重命名一个KEY
     *
     * @param  string $key     键名
     * @param  string $new_key 新的键名
     * @return boolean
     */
    public function rename($key, $new_key)
    {
        return $this->handler->rename($key, $new_key);
    }

    /**
     * 复制一个KEY的VALUE到一个新的KEY
     *
     * @param  string $key     键名
     * @param  string $new_key 新的键名
     * @return boolean
     */
    public function renameNx($key, $new_key)
    {
        return $this->handler->renameNx($key, $new_key);
    }

    /**
     * 清空当前数据库
     *
     * @return boolean
     */
    public function flushDB()
    {
        return $this->handler->flushDB();
    }

    /**
     * 清空所有数据库
     *
     * @return boolean
     */
    public function flushAll()
    {
        return $this->handler->flushAll();
    }

    /**
     * 返回当前库状态
     *
     * @return array
     */
    public function info()
    {
        return $this->handler->info();
    }

    /**
     * 重置状态
     *
     * @return boolean
     */
    public function resetStat()
    {
        return $this->handler->resetStat();
    }

    /**
     * 同步保存数据到磁盘
     * 
     * @return boolean
     */
    public function save()
    {
        return $this->handler->save();
    }

    /**
     * 异步保存数据到磁盘
     * 
     * @return boolean
     */
    public function bgSave()
    {
        return $this->handler->bgSave();
    }

    /**
     * 返回最后保存到磁盘的时间
     */
    public function lastSave()
    {
        return $this->handler->lastSave();
    }

    /**
     * 返回key,支持*多个字符，?一个字符
     * 只有*　表示全部
     *
     * @param string $key   键
     * @return array
     */
    public function keys($key)
    {
        return $this->handler->keys($key);
    }

    /**
     * 返回一个key的数据类型
     *
     * @param  string $key 键
     * @return string
     */
    public function type($key)
    {
        return $this->handler->type($key);
    }

    /**
     * 发送一个字符串到Redis,返回一个相同的字符串
     *
     * @param  string $string 字符串
     * @return string
     */
    public function out($string)
    {
        return $this->handler->echo($string);
    }

    /**
     * 为一个key设定过期时间, 单位为秒
     *
     * @param string  $key       键
     * @param integer $expire    过期时间，单位秒
     * @return boolean
     */
    public function expire($key, $expire)
    {
        return $this->handler->expire($key, $expire);
    }

    /**
     * 为一个key设定过期时间, 单位为毫秒
     *
     * @param  string  $key    键
     * @param  integer $expire 过期时间，单位毫秒
     * @return boolean
     */
    public function pexpire($key, $expire)
    {
        return $this->handler->pexpire($key, $expire);
    }

    /**
     * 为一个key设定生命周期
     *
     * @param  string $key    key名称
     * @param  integer $expire 过期时间, Unix时间戳
     * @return boolean
     */
    public function expireAt($key, $expire)
    {
        return $this->handler->expireAt($key, $expire);
    }

    /**
     * 删除一个KEY的生命周期设置
     *
     * @param  string $key 键
     * @return boolean
     */
    public function persist($key)
    {
        return $this->handler->persist($key);
    }

    /**
     * 返回一个key还有多久过期，单位秒
     *
     * @param string $key   键
     * @return integer
     */
    public function ttl($key)
    {
        return $this->handler->ttl($key);
    }

    /**
     * 返回一个key还有多久过期, 单位毫秒
     *
     * @param  string $key 键
     * @return integer
     */
    public function pttl($key)
    {
        return $this->handler->pttl($key);
    }

    /**
     * 关闭服务器链接
     * 
     * @return void
     */
    public function close()
    {
        return $this->handler->close();
    }

    /**
     * 返回当前数据库key数量
     * 
     * @return integer
     */
    public function dbSize()
    {
        return $this->handler->dbSize();
    }

    /**
     * 返回一个随机key
     * 
     * @return string
     */
    public function randomKey()
    {
        return $this->handler->randomKey();
    }

    /**
     * 设置客户端的选项
     *
     * @param string $key   键
     * @param string $value 值
     * @return boolean
     */
    public function setOption($key, $value)
    {
        return $this->handler->setOption($key, $value);
    }

    /**
     * 取得客户端的选项
     *
     * @param string $key   键
     * @return mixed
     */
    public function getOption($key)
    {
        return $this->handler->getOption($key);
    }

    /**
     * 使用aof来进行数据库持久化
     *
     * @return boolean
     */
    public function bgrewriteaof()
    {
        return $this->handler->bgrewriteaof();
    }

    /**
     * 选择从服务器
     *
     * @param  string  $ip   IP
     * @param  integer $port 端口
     * @return boolean
     */
    public function slaveof($ip, $port)
    {
        return $this->handler->slaveof($ip, $port);
    }

    /**
     * 声明一个对象，并指向KEY
     *
     * @param  string $type 检索的类型
     * @param  string $key  key名
     * @return mixed
     */
    public function object($type, $key)
    {
        if (!in_array($type, ['encoding', 'refcount', 'idletime'])) {
            throw new InvalidArgumentException('object type faild');
        }
        return $this->handler->object($type, $key);
    }

    /**
     * 设置REIDS系统配置
     *
     * @param string $key   键
     * @param string $value 值
     * @return boolean
     */
    public function setConfig($key, $value)
    {
        return $this->handler->config('SET', $key, $value);
    }

    /**
     * 获取REIDS系统配置, *表示所有
     *
     * @param string $key   键，*表示所有
     * @return mixed
     */
    public function getConfig($key)
    {
        return $this->handler->config('GET', $key);
    }

    /**
     * 在服务器端执行LUA脚本
     *
     * @param  string $script Lua脚本
     * @return mixed
     */
    public function run($script)
    {
        return $this->handler->eval($script);
    }

    /**
     * 取得最后的错误消息
     *
     * @return mixed
     */
    public function getLastError()
    {
        return $this->handler->getLastError();
    }

    /**
     * 把一个KEY从REIDS中销毁, 可以使用RESTORE函数恢复出来。
     * 使用DUMP销毁的VALUE, 函数将返回这个数据在REIDS中的二进制内存地址
     *
     * @param  string $key 键
     * @return mixed
     */
    public function dump($key)
    {
        return $this->handler->dump($key);
    }

    /**
     * 恢复DUMP函数销毁的VALUE到一个新的KEY上
     *
     * @param  string  $key    新的key名
     * @param  string  $value  dump返回的地址值
     * @param  integer $expire 生存时间, 0则不设置
     * @return mixed
     */
    public function restore($key, $value, $expire = 0)
    {
        return $this->handler->restore($key, $expire, $value);
    }

    /**
     * 返回当前REDIS服务器的生存时间
     *
     * @return mixed
     */
    public function time()
    {
        return $this->handler->time();
    }
}
