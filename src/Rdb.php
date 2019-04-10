<?php
namespace mon\store;

use Redis;
use BadFunctionCallException;
use InvalidArgumentException;

/**
 * Redis操作类
 *
 * @author Mon <985558837@qq.com>
 * @version 1.0 2018-05-20
 */
class Redis
{
    /**
     * redis链接实例
     * 
     * @var [type]
     */
    private $handler;

    /**
     * 配置信息
     *
     * @var array
     */
    private $config = [
        'host'       => '127.0.0.1',
        'port'       => 6379,
        'password'   => '',
    ];

    /**
     * 构造函数
     *
     * @param array $config 配置信息
     */
    public function __construct($config = [])
    {
        if(!extension_loaded('redis')){
            throw new BadFunctionCallException('not support: redis');
        }
        if(empty($config)){
            $this->config = array_merge($this->config, $config);
        }
        
        $this->handler = new Redis();
        $this->handler->connect($this->config['host'], $this->config['port']);
        if($this->config['password']){
            $this->handler->auth($this->config['password']);
        }
    }

    /**
     * 执行原生的redis操作
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
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function get($key)
    {
        return $this->handler->get($key);
    }

    /**
     * 获取多个key值
     *
     * @param  array  $key [description]
     * @return [type]      [description]
     */
    public function mGet(array $key)
    {
        return $this->handler->mGet($key);
    }

    /**
     * 返回字符串的一部分
     *
     * @param  [type] $key   key名
     * @param  [type] $start 起始点
     * @param  [type] $end   结束点
     * @return [type]        [description]
     */
    public function getRange($key, $start, $end)
    {
        return $this->handler->getRange($key, $start, $end);
    }

    /**
     * 返回字符串长度
     *
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function strlen($key)
    {
        return $this->handler->strlen($key);
    }

    /**
     * 获取key的原值，并设置新值，不存在原值则返回false
     *
     * @param  [type] $key   [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function getSet($key, $value)
    {
        return $this->handler->getSet($key, $value);
    }

    /**
     * 设置一个key值
     *
     * @param [type] $key     [description]
     * @param [type] $value   [description]
     * @param [type] $options [description]
     */
    public function set($key, $value, $options = '')
    {
        return $this->handler->set($key, $value, $options);
    }

    /**
     * 添加指定的字符串到指定的字符串key
     *
     * @param  [type] $key   [description]
     * @param  [type] $value [description]
     * @return [type]        返回新的key size
     */
    public function append($key, $value)
    {
        return $this->handler->append($key, $value);
    }

    /**
     * 设置一个有过期时间的key值, 单位秒
     * 
     * @param  [type] $key    [description]
     * @param  [type] $expire [description]
     * @param  [type] $value  [description]
     * @return [type]         [description]
     */
    public function setex($key, $expire, $value)
    {
        return $this->handler->setex($key, $expire, $value);
    }

    /**
     * 设置一个有过期时间的key值, 单位毫秒
     * 
     * @param  [type] $key    [description]
     * @param  [type] $expire [description]
     * @param  [type] $value  [description]
     * @return [type]         [description]
     */
    public function psetex($key, $expire, $value)
    {
        return $this->handler->psetex($key, $expire, $value);
    }

    /**
     * 设置一个key值,如果key存在,不做任何操作
     * 
     * @param  [type] $key   [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function setnx($key, $value)
    {
        return $this->handler->setnx($key, $value);
    }

    /**
     * 替换字符串的一部分, 主要配置setex, 实现更新值有效时间不更新
     *
     * @param [type]  $key    key
     * @param [type]  $value  值
     * @param integer $offset offset
     */
    public function setRange($key, $value, $offset = 0)
    {
        return $this->handler->setRange($key, $offset, $value);
    }

    /**
     * 批量设置key值
     * 
     * @param  [type] $array [description]
     * @return [type]        [description]
     */
    public function mSet($array)
    {
        return $this->handler->mSet($array);
    }

    /**
     * 移除已经存在key
     *
     * @param  [type] $key key名，字符串或者数组
     * @return [type]      [description]
     */
    public function delete($key)
    {
        return $this->handler->delete($key);
    }

    /**
     * 判断一个key值是不是存在
     *
     * @param [type] $key
     */
    public function exists($key)
    {
        return $this->handler->exists($key);
    }

    /**
     * 对key的值加value, 相当于 key = key + value
     *
     * @param  [type] $key   [description]
     * @param  int    $value [description]
     * @return [type]        返回新的INT数值
     */
    public function incrBy($key, $value)
    {
        return $this->handler->incrBy($key, $value);
    }

    /**
     * 对key的值加value, 相当于 key = key + value
     *
     * @param  [type] $key   [description]
     * @param  float  $value [description]
     * @return [type]        返回新的INT数值
     */
    public function incrByFloat($key, $value)
    {
        return $this->handler->incrByFloat($key, $value);
    }

    /**
     * 对key的值减value, 相当于 key = key - value
     *
     * @param  [type] $key   [description]
     * @param  int    $value [description]
     * @return [type]        返回新的INT数值
     */
    public function decrBy($key, $value)
    {
        return $this->handler->decrBy($key, $value);
    }

    /**
     * 对key的值减value, 相当于 key = key - value
     *
     * @param  [type] $key   [description]
     * @param  float  $value [description]
     * @return [type]        返回新的INT数值
     */
    public function decrByFloat($key, $value)
    {
        return $this->handler->decrByFloat($key, $value);
    }

    /***************** hash类型操作函数 *******************/

    /**
     * 为hash表设定一个字段的值
     * 
     * @param  [type] $key   [description]
     * @param  [type] $field [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function hSet($key, $field, $value)
    {
        return $this->handler->hSet($key, $field, $value);
    }

    /**
     * 得到hash表中一个字段的值
     * 
     * @param  [type] $key   [description]
     * @param  [type] $field [description]
     * @return [type]        [description]
     */
    public function hGet($key, $field)
    {
        return $this->handler->hGet($key, $field);
    }

    /**
     * 删除hash表中指定字段 ,支持批量删除
     *
     * @param  [type] $key   [description]
     * @param  [type] $field [description]
     * @return [type]        [description]
     */
    public function hDel($key, $field)
    {
        $delNum = 0;
        if(is_array($field)){
            // 字符串，批量删除
            foreach($field as $row)
            {
                $delNum += $this->handler->hDel($key, $row);
            }
        }
        else{
            // 字符串，删除单个
            $delNum += $this->handler->hDel($key, $field);
        }
 
        return $delNum;
    }

    /**
     * 返回hash表元素个数
     *
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function hLen($key)
    {
        return $this->handler->hLen($key);
    }

    /**
     * 为hash表设定一个字段的值,如果字段存在，返回false
     *
     * @param  [type] $key   [description]
     * @param  [type] $field [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function hSetNx($key, $field, $value)
    {
        return $this->handler->hSetNx($key, $field, $value);
    }

    /**
     * 为hash表多个字段设定值。
     * 
     * @param  [type] $key   [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function hMset($key, $value)
    {
        if(!is_array($value)){
            return false;
        }

        return $this->handler->hMset($key, $value); 
    }

    /**
     * 获取hash表多个字段值。
     * @param string $key
     * @param array|string $value string以','号分隔字段
     * @return array|bool
     */
    public function hMget($key, $field)
    {
        if(!is_array($field)){
            $field = explode(',', $field);
        }

        return $this->handler->hMget($key, $field);
    }

    /**
     * 为hash表的某个值累加整数，可以负数
     * 
     * @param string $key   key值
     * @param int $field    字段
     * @param string $value 步长
     * @return bool
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
     * @param int $field    字段
     * @param string $value 步长
     * @return bool
     */
    public function hIncrByFloat($key, $field, $value)
    {
        $value = floatval($value);

        return $this->handler->hIncrByFloat($key, $field, $value);
    }

    /**
     * 返回所有hash表的所有字段
     *
     * @param string $key
     * @return array|bool
     */
    public function hKeys($key)
    {
        return $this->handler->hKeys($key);
    }

    /**
     * 返回所有hash表的字段值，为一个索引数组
     * 
     * @param string $key
     * @return array|bool
     */
    public function hVals($key)
    {
        return $this->handler->hVals($key);
    }

    /**
     * 验证HASH表中是否存在指定的KEY-VALUE
     *
     * @param  [type] $key   [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function hExists($key, $value)
    {
        return $this->handler->hExists($key, $value);
    }

    /**
     * 返回所有hash表的字段值，为一个关联数组
     * 
     * @param string $key
     * @return array|bool
     */
    public function hGetAll($key)
    {
        return $this->handler->hGetAll($key);
    }

    /********************* List队列类型操作命令 ************************/

    /**
     * 在队列尾部插入一个元素
     * 
     * @param [type] $key
     * @param [type] $value
     * 返回队列长度
     */
    public function rPush($key, $value)
    {
        return $this->handler->rPush($key, $value); 
    }
     
    /**
     * 在队列尾部插入一个元素 如果key不存在，什么也不做
     * 
     * @param [type] $key
     * @param [type] $value
     * @return 返回队列长度
     */
    public function rPushx($key, $value)
    {
        return $this->handler->rPushx($key, $value);
    }
     
    /**
     * 在队列头部插入一个元素
     * 
     * @param [type] $key
     * @param [type] $value
     * @return 返回队列长度
     */
    public function lPush($key, $value)
    {
        return $this->handler->lPush($key, $value);
    }
     
    /**
     * 在队列头插入一个元素 如果key不存在，什么也不做
     * @param [type] $key
     * @param [type] $value
     * @return 返回队列长度
     */
    public function lPushx($key, $value)
    {
        return $this->handler->lPushx($key, $value);
    }
     
    /**
     * 返回队列长度
     * 
     * @param [type] $key
     */
    public function lLen($key)
    {
        return $this->handler->lLen($key); 
    }

    /**
     * 返回队列大小
     *
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function lSize($key)
    {
        return $this->handler->lSize($key)
    }

    /**
     * 返回队列指定区间的元素
     * 
     * @param [type] $key
     * @param [type] $start
     * @param [type] $end
     */
    public function lRange($key, $start, $end)
    {
        return $this->handler->lrange($key, $start, $end);
    }

    /**
     * 截取LIST中指定范围内的元素组成一个新的LIST并指向KEY
     *
     * @param  [type] $key   [description]
     * @param  [type] $start [description]
     * @param  [type] $end   [description]
     * @return [type]        [description]
     */
    public function lTrim($key, $start, $end)
    {
        return $this->handler->lTrim($key, $start, $end);
    }

    /**
     * 返回队列中指定索引的元素
     * 
     * @param [type] $key
     * @param [type] $index
     */
    public function lIndex($key, $index)
    {
        return $this->handler->lIndex($key, $index);
    }

    /**
     * 根据索引值返回指定KEY-LIST中的元素，0为第一个
     *
     * @param  [type] $key   [description]
     * @param  [type] $index [description]
     * @return [type]        [description]
     */
    public function lGet($key, $index)
    {
        return $this->handler->lGet($key, $index);
    }

    /**
     * 设定队列中指定index的值。
     * 
     * @param [type] $key
     * @param [type] $index
     * @param [type] $value
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
     * @param [type] $key
     * @param [type] $count
     * @param [type] $value
     */
    public function lRem($key, $count, $value)
    {
        return $this->handler->lRem($key, $value, $count);
    }
     
    /**
     * 删除并返回队列中的头元素。
     * 
     * @param [type] $key
     */
    public function lPop($key)
    {
        return $this->handler->lPop($key);
    }
     
    /**
     * 删除并返回队列中的尾元素
     * 
     * @param [type] $key
     */
    public function rPop($key)
    {
        return $this->handler->rPop($key);
    }

    /**
     * 从key-LIST的最后弹出一个元素，并且把这个元素从target-LIST的顶部压入target-LIST中
     *
     * @param  [type] $key        [description]
     * @param  [type] $target_key [description]
     * @return [type]             [description]
     */
    public function rpoplpush($key, $target_key)
    {
        return $this->handler->rpoplpush($key, $target_key);
    }

    /************* 无序集合操作命令 *****************/

    /**
     * 返回集合中所有元素
     *
     * @param [type] $key
     */
    public function sMembers($key)
    {
        return $this->handler->sMembers($key);
    }

    /**
     * 检查VALUE是否是key-SET容器中的成员
     *
     * @param  [type] $key   [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function sIsMember($key, $value)
    {
        return $this->handler->sIsMember($key, $value);
    }
    
    /**
     * 添加集合。由于版本问题，扩展不支持批量添加。这里做了封装
     *
     * @param [type] $key
     * @param string|array $value
     * @return 增加数
     */
    public function sAdd($key, $value)
    {
        if(!is_array($value)){
            $arr = array($value);
        }
        else{
            $arr = $value;
        }

        foreach($arr as $row)
        {
            $this->handler->sAdd($key, $row);
        }
    }

    /**
     * 返回无序集合的元素个数
     *
     * @param [type] $key
     */
    public function sCard($key)
    {
        return $this->handler->sCard($key);
    }

    /**
     * 随机返回一个元素，并且在key-SET容器中移除该元素。
     *
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function sPop($key)
    {
        return $this->handler->sPop($key);
    }

    /**
     * 取得指定key-SET容器中的一个随机元素，但不会在key-SET容器中移除它
     *
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function sRandMember($key)
    {
        return $this->handler->sRandMember($key);
    }

    /**
     * 从集合中删除一个元素
     *
     * @param [type] $key
     * @param [type] $value
     */
    public function sRem($key, $value)
    {
        return $this->handler->sRem($key, $value);
    }

    /**
     * 移动一个指定的MEMBER从key-SET到指定的target-SET中
     *
     * @param  [type] $key    [description]
     * @param  [type] $target [description]
     * @param  [type] $member [description]
     * @return [type]         [description]
     */
    public function sMove($key, $target, $member)
    {
        return $this->handler->sMove($key, $target, $member);
    }

    /**
     * 返回指定两个SETS集合的交集结果，注意：原生的sInter可传N个key名
     *
     * @param  [type] $key1 [description]
     * @param  [type] $key2 [description]
     * @return [type]       [description]
     */
    public function sInter($key1, $key2)
    {
        return $this->handler->sInter($key1, $key2);
    }

    /**
     * 执行一个交集操作，并把结果存储到一个新的SET容器中
     *
     * @param  [type] $name 新的key名
     * @param  [type] $key1 [description]
     * @param  [type] $key2 [description]
     * @return [type]       [description]
     */
    public function sInterStore($name, $key1, $key2)
    {
        return $this->handler->sInterStore($name, $key1, $key2);
    }

    /**
     * 返回指定两个SETS集合的并集结果，注意：原生的sUnion可传N个key名
     *
     * @param  [type] $key1 [description]
     * @param  [type] $key2 [description]
     * @return [type]       [description]
     */
    public function sUnion($key1, $key2)
    {
        return $this->handler->sUnion($key1, $key2);
    }

    /**
     * 执行一个并集操作，并把结果存储到一个新的SET容器中
     *
     * @param  [type] $name 新的key名
     * @param  [type] $key1 [description]
     * @param  [type] $key2 [description]
     * @return [type]       [description]
     */
    public function sUnionStore($name, $key1, $key2)
    {
        return $this->handler->sUnionStore($name, $key1, $key2);
    }

    /**
     * 求2个集合的差集
     *
     * @param [type] $key1
     * @param [type] $key2
     */
    public function sDiff($key1, $key2)
    {
        return $this->handler->sDiff($key1, $key2);
    }

    /**
     * 执行一个差集操作，并把结果存储到一个新的SET容器中
     *
     * @param  [type] $name [description]
     * @param  [type] $key1 [description]
     * @param  [type] $key2 [description]
     * @return [type]       [description]
     */
    public function sDiffStore($name, $key1, $key2)
    {
        return $this->handler->sDiffStore($name, $key1, $key2);
    }

    /**
     * 筛选集合
     *
     * @param  [type] $key    [description]
     * @param  array  $option [description]
     * @return [type]         [description]
     */
    public function sort($key, array $option)
    {
        return $this->handler->sort($key, $option);
    }

    /********************* sorted set有序集合类型操作命令 *********************/

    /**
     * 给当前集合添加一个元素，如果value已经存在，会更新order的值。
     * 
     * @param string $key
     * @param string $order 序号
     * @param string $value 值
     * @return bool
     */
    public function zAdd($key, $order, $value)
    {
        return $this->handler->zAdd($key, $order, $value);   
    }

    /**
     * 从有序集合中删除指定的成员
     *
     * @param  [type] $key   [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function zDelete($key, $value)
    {
        return $this->handler->zDelete($key, $value);
    }

    /**
     * 给$value成员的order值，增加$num,可以为负数
     * 
     * @param string $key
     * @param string $num 序号
     * @param string $value 值
     * @return 返回新的order
     */
    public function zinCry($key, $num, $value)
    {
        return $this->handler->zinCry($key, $num, $value);
    }

    /**
     * 删除值为value的元素
     * 
     * @param string $key
     * @param stirng $value
     * @return bool
     */
    public function zRem($key, $value)
    {
        return $this->handler->zRem($key,$value);
    }

    /**
     * 集合以order递增排列后，0表示第一个元素，-1表示最后一个元素
     * 
     * @param string $key
     * @param int $start
     * @param int $end
     * @return array|bool
     */
    public function zRange($key, $start, $end)
    {
        return $this->handler->zRange($key, $start, $end);
    }

    /**
     * 集合以order递减排列后，0表示第一个元素，-1表示最后一个元素
     * 
     * @param string $key
     * @param int $start
     * @param int $end
     * @return array|bool
     */
    public function zRevRange($key, $start, $end)
    {
        return $this->handler->zRevRange($key, $start, $end);
    }

    /**
     * 集合以order递增排列后，返回指定order之间的元素。
     * min和max可以是-inf和+inf　表示最大值，最小值
     * 
     * @param string $key
     * @param int $start
     * @param int $end
     * @package array $option 参数
     *     withscores=>true，表示数组下标为Order值，默认返回索引数组
     *     limit=>array(0,1) 表示从0开始，取一条记录。
     * @return array|bool
     */
    public function zRangeByScore($key, $start='-inf', $end="+inf", $option=array())
    {
        return $this->handler->zRangeByScore($key, $start, $end, $option);
    }
     
    /**
     * 集合以order递减排列后，返回指定order之间的元素。
     * min和max可以是-inf和+inf　表示最大值，最小值
     * 
     * @param string $key
     * @param int $start
     * @param int $end
     * @package array $option 参数
     *     withscores=>true，表示数组下标为Order值，默认返回索引数组
     *     limit=>array(0,1) 表示从0开始，取一条记录。
     * @return array|bool
     */
    public function zRevRangeByScore($key, $start='-inf', $end="+inf", $option=array())
    {
        return $this->handler->zRevRangeByScore($key, $start, $end, $option);
    }
     
    /**
     * 返回order值在start end之间的数量
     * 
     * @param [type] $key
     * @param [type] $start
     * @param [type] $end
     */
    public function zCount($key, $start, $end)
    {
        return $this->handler->zCount($key, $start, $end);
    }
     
    /**
     * 返回值为value的order值
     * 
     * @param [type] $key
     * @param [type] $value
     */
    public function zScore($key,$value)
    {
        return $this->handler->zScore($key, $value);
    }
     
    /**
     * 返回集合以score递增加排序后，指定成员的排序号，从0开始。
     * 
     * @param [type] $key
     * @param [type] $value
     */
    public function zRank($key, $value)
    {
        return $this->handler->zRank($key, $value);
    }
     
    /**
     * 返回集合以score递增加排序后，指定成员的排序号，从0开始。
     * 
     * @param [type] $key
     * @param [type] $value
     */
    public function zRevRank($key, $value)
    {
        return $this->handler->zRevRank($key, $value);
    }
     
    /**
     * 删除集合中，score值在start end之间的元素　包括start end
     * min和max可以是-inf和+inf　表示最大值，最小值
     * 
     * @param [type] $key
     * @param [type] $start
     * @param [type] $end
     * @return 删除成员的数量。
     */
    public function zRemRangeByScore($key, $start, $end)
    {
        return $this->handler->zRemRangeByScore($key, $start, $end);
    }

    /**
     * 删除集合中，score值在start end之间的元素　不包括start end
     *
     * @param  [type] $key   [description]
     * @param  [type] $start [description]
     * @param  [type] $end   [description]
     * @return [type]        [description]
     */
    public function zRemRangeByRank($key, $start, $end)
    {
        return $this->handler->zRemRangeByRank($key, $start, $end);
    }

    /**
     * 返回集合元素个数。
     * 
     * @param [type] $key
     */
    public function zCard($key)
    {
        return $this->handler->zCard($key);
    }

    /**
     * 返回集合元素个数。
     *
     * @param  [type] $key [description]
     * @return [type]      [description]
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
     * @param  [type] $key    [description]
     * @param  [type] $value  [description]
     * @param  [type] $member [description]
     * @return [type]         [description]
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
     * @param [type] $key
     */
    public function watch($key)
    {
        return $this->handler->watch($key);
    }
     
    /**
     * 取消当前链接对所有key的watch
     *  EXEC 命令或 DISCARD 命令先被执行了的话，那么就不需要再执行 UNWATCH 了
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
     */
    public function multi($type = Redis::MULTI)
    {
        return $this->handler->multi($type);
    }
     
    /**
     * 执行一个事务
     * 收到 EXEC 命令后进入事务执行，事务中任意命令执行失败，其余的命令依然被执行
     */
    public function exec()
    {
        return $this->handler->exec();
    }
     
    /**
     * 回滚一个事务
     */
    public function discard()
    {
        return $this->handler->discard();
    }

    /************* 订阅操作命令 *****************/

    /**
     * 订阅频道
     *
     * @param  [type] $key      订阅的频道名，可字符串，可数组
     * @param  [type] $callback 回调函数，function($redis, $chan, $msg){}
     * @return [type]           [description]
     */
    public function subscribe($key, $callback)
    {
        return $this->handler->subscribe($key, $callback);
    }

    /**
     * 发布订阅
     *
     * @param  [type] $channel 发布的频道
     * @param  [type] $messgae 发布信息
     * @return int             订阅数
     */
    public function publish($channel, $messgae)
    {
        return $this->handler->publish($channel, $messgae);
    }


    /************* 管理操作命令 *****************/

    /**
     * 测试当前链接是不是已经失效
     * 没有失效返回+PONG
     * 失效返回false
     */
    public function ping()
    {
        return $this->handler->ping();
    }
    
    /**
     * 密码认证
     *
     * @param  [type] $auth [description]
     * @return [type]       [description]
     */
    public function auth($auth)
    {
        return $this->handler->auth($auth);
    }

    /**
     * 选择数据库
     *
     * @param int $dbId 数据库ID号
     * @return bool
     */
    public function select($dbId)
    {
        return $this->handler->select($dbId);
    }

    /**
     * 移动一个KEY-VALUE到另一个DB
     *
     * @param  [type] $key     key值
     * @param  [type] $dbindex 要移动到的数据库ID
     * @return [type]          [description]
     */
    public function move($key, $dbindex)
    {
        return $this->handler->move($key, $dbindex);
    }

    /**
     * 重命名一个KEY
     *
     * @param  [type] $key     [description]
     * @param  [type] $new_key [description]
     * @return [type]          [description]
     */
    public function rename($key, $new_key)
    {
        return $this->handler->rename($key, $new_key);
    }

    /**
     * 复制一个KEY的VALUE到一个新的KEY
     *
     * @param  [type] $key     [description]
     * @param  [type] $new_key [description]
     * @return [type]          [description]
     */
    public function renameNx($key, $new_key)
    {
        return $this->handler->renameNx($key, $new_key);
    }

    /**
     * 清空当前数据库
     *
     * @return bool
     */
    public function flushDB()
    {
        return $this->handler->flushDB();
    }

    /**
     * 清空所有数据库
     *
     * @return bool
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
     * @return [type] [description]
     */
    public function resetStat()
    {
        return $this->handler->resetStat();
    }

    /**
     * 同步保存数据到磁盘
     */
    public function save()
    {
        return $this->handler->save();
    }
     
    /**
     * 异步保存数据到磁盘
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
     * @param string $key
     * @return array
     */
    public function keys($key)
    {
        return $this->handler->keys($key);
    }

    /**
     * 返回一个key的数据类型
     *
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function type($key)
    {
        return $this->handler->type($key);
    }

    /**
     * 发送一个字符串到Redis,返回一个相同的字符串
     *
     * @param  [type] $string [description]
     * @return [type]         [description]
     */
    public function echo($string)
    {
        return $this->handler->echo($string);
    }

    /**
     * 为一个key设定过期时间, 单位为秒
     *
     * @param [type] $key
     * @param [type] $expire
     */
    public function expire($key, $expire)
    {
        return $this->handler->expire($key,$expire);
    }

    /**
     * 为一个key设定过期时间, 单位为毫秒
     *
     * @param  [type] $key    [description]
     * @param  [type] $expire [description]
     * @return [type]         [description]
     */
    public function pexpire($key, $expire)
    {
        return $this->handler->pexpire($key, $expire);
    }

    /**
     * 为一个key设定生命周期
     *
     * @param  [type] $key    key名称
     * @param  [type] $expire 过期时间, Unix时间戳
     * @return [type]         [description]
     */
    public function expireAt($key, $expire)
    {
        return $this->handler->expireAt($key, $expire);
    }

    /**
     * 删除一个KEY的生命周期设置
     *
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function persist($key)
    {
        return $this->handler->persist($key);
    }

    /**
     * 返回一个key还有多久过期，单位秒
     *
     * @param [type] $key
     */
    public function ttl($key)
    {
        return $this->handler->ttl($key);
    }

    /**
     * 返回一个key还有多久过期, 单位毫秒
     *
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function pttl($key)
    {
        return $this->handler->pttl($key);
    }
     
    /**
     * 设定一个key什么时候过期，time为一个时间戳
     *
     * @param [type] $key
     * @param [type] $time
     */
    public function exprieAt($key, $time)
    {
        return $this->handler->expireAt($key,$time);
    }
     
    /**
     * 关闭服务器链接
     */
    public function close()
    {
        return $this->handler->close();
    }

    /**
     * 返回当前数据库key数量
     */
    public function dbSize()
    {
        return $this->handler->dbSize();
    }
     
    /**
     * 返回一个随机key
     */
    public function randomKey()
    {
        return $this->handler->randomKey();
    }

    /**
     * 设置客户端的选项
     *
     * @param [type] $key   [description]
     * @param [type] $value [description]
     */
    public function setOption($key, $value)
    {
        return $this->handler->setOption($key, $value);
    }

    /**
     * 取得客户端的选项
     *
     * @param [type] $key   [description]
     */
    public function getOption($key)
    {
        return $this->handler->getOption($key);
    }

    /**
     * 使用aof来进行数据库持久化
     *
     * @return [type] [description]
     */
    public function bgrewriteaof()
    {
        return $this->handler->bgrewriteaof();
    }

    /**
     * 选择从服务器
     *
     * @param  [type] $ip   [description]
     * @param  [type] $port [description]
     * @return [type]       [description]
     */
    public function slaveof($ip, $port)
    {
        return $this->handler->slaveof($ip, $port);
    }

    /**
     * 声明一个对象，并指向KEY
     *
     * @param  [type] $type 检索的类型
     * @param  [type] $key  key名
     * @return [type]       [description]
     */
    public function object($type, $key)
    {
        if(!in_array($type, ['encoding', 'refcount', 'idletime'])){
            throw new InvalidArgumentException('object type faild');
        }
        return $this->handler->object($type, $key);
    }

    /**
     * 设置REIDS系统配置
     *
     * @return [type] [description]
     */
    public function setConfig($key, $value)
    {
        return $this->handler->config('SET', $key, $value);
    }

    /**
     * 获取REIDS系统配置, *表示所有
     *
     * @return [type] [description]
     */
    public function setConfig($key)
    {
        return $this->handler->config('GET', $key);
    }

    /**
     * 在服务器端执行LUA脚本
     *
     * @param  [type] $script LUA脚本
     * @return [type]         [description]
     */
    public function eval($script)
    {
        return $this->handler->eval($script);
    }

    /**
     * 取得最后的错误消息
     *
     * @return [type] [description]
     */
    public function getLastError()
    {
        return $this->handler->getLastError();
    }

    /**
     * 把一个KEY从REIDS中销毁, 可以使用RESTORE函数恢复出来。
     * 使用DUMP销毁的VALUE, 函数将返回这个数据在REIDS中的二进制内存地址
     *
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function dump($key)
    {
        return $this->handler->dump($key);
    }

    /**
     * 恢复DUMP函数销毁的VALUE到一个新的KEY上
     *
     * @param  [type]  $key    新的key名
     * @param  [type]  $value  dump返回的地址值
     * @param  integer $expire 生存时间, 0则不设置
     * @return [type]          [description]
     */
    public function restore($key, $value, $expire = 0)
    {
        return $this->handler->restore($key, $expire, $value);
    }

    /**
     * 返回当前REDIS服务器的生存时间
     *
     * @return [type] [description]
     */
    public function time()
    {
        return $this->handler->time();
    }

}
