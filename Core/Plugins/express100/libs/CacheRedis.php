<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/6/11 0011
 * Time: 上午 11:45
 * reids总共只有16个库
 */
class CacheRedis
{
    //redis主机
    private static $host='127.0.0.1';

    //redis端口
    private static $port='6379';

    //redis验证密码
    private static $pass='baiy@redis';

    //redis连接超时设置
    private static $connectTime=2;

    private static $redis=null;

    /**
     * 初始化redis
     */
    public static function init()
    {
        try {
            if (empty(static::$redis)) {
                static::$redis = new Redis();
                static::$redis->connect(static::$host, static::$port, static::$connectTime);
                static::$redis->auth(static::$pass);
                static::$redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE);
            }
        }catch (Exception $e){

        }
    }

    /**
     * 选择数据库
     * @param int $db
     * @return bool
     */
    public static function selectDb($db=0)
    {
        return static::$redis->select($db);
    }

    /**
     * 存入数据库
     * @param $key
     * @param $value
     * @param int $expireTime
     * @return bool
     */
    public static function set($key,$value,$expireTime=600)
    {
        return static::$redis->set($key,$value,$expireTime);
    }

    /**
     * 取出数据
     * @param $key
     * @return bool|mixed
     */
    public static function get($key)
    {
        return static::$redis->get($key);
    }

    /**
     * 数组存入数据库
     * @param $key
     * @param $value＝[]
     * @param int $expireTime
     * @return mixed
     */
    public static function mset($key,$value,$expireTime=600)
    {
        return static::$redis->set($key,serialize($value),$expireTime);
    }

    /**
     * 从数据库中取出数据
     * @param $key
     * @return mixed
     */
    public static function mget($key)
    {
        return unserialize(static::$redis->get($key));
    }

    /**
     * 向redis链表尾部压入一个值
     * @param $list
     * @param $value
     * @return mixed
     */
    public static function rpush($list,$value)
    {
        return static::$redis->rPush($list,$value);
    }

    /**
     * 设置key的存活时间
     * @param $key
     * @param int $expireTime
     * @return mixed
     */
    public static function setTime($key, $expireTime = 60)
    {
        return static::$redis->setTimeout($key, $expireTime);
    }

    /**
     * 从redis链表头部取出一个值
     * @param $list
     * @return mixed
     */
    public static function lpop($list)
    {
        return static::$redis->lPop($list);
    }

    /**
     * 根据区间从redis链表中取出数据
     * @param $list
     * @param int $head
     * @param int $tail
     * @return mixed
     */
    public static function lrange($list,$head=0,$tail=-1)
    {
        return static::$redis->lRange($list,$head,$tail);
    }

    /**
     * @param $key
     * @return mixed
     * 获得list长度
     */
    public static function getListSize($key)
    {
        return static::$redis->lSize($key);
    }

    /**
     * 刷新选中的数据库
     * @return mixed
     */
    public static function flushDb()
    {
        return static::$redis->flushDb();
    }

    /**
     * @return mixed
     * 刷新所有数据库
     */
    public static function flushAllDb()
    {
        return static::$redis->flushAll();
    }

    /**
     * 键值自动加1
     * @param $key
     * @param $expireTime
     * @return bool
     */
    public static function autoIncrement($key,$expireTime=3600)
    {
        $data=static::get($key);
        if(empty($data)){
            return static::set($key,1,$expireTime);
        }else{
            return static::$redis->incr($key);
        }
    }

    /**
     * 键值自动减1
     * @param $key
     * @return mixed
     */
    public static function autoDecrease($key)
    {
        return static::$redis->decr($key);
    }

    /**
     * 获得所有keys
     * @return bool|[]
     */
    public static function getAllKeys()
    {
        return static::$redis->keys('*');
    }
}