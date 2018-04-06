<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/9
 * Time: 16:53
 */

class Db
{
    //数据库主机
    private static $host='192.168.1.160';

    //数据库端口
    private static $port=3306;

    //数据库用户名
    private static $user='root';

    //数据库密码
    private static $password='123456';

    //数据库名称
    private static $database='byjk';

    //数据库连接超时
    private static $connTime=20;

    //数据库字符编码设置
    private static $charset='utf8';

    public static $conn=null;

    /**
     * 数据库初始化
     */
    public static function init()
    {
        static::connect();
    }

    /**
     * 数据库建立连接
     */
    protected static function connect()
    {
        if(empty(static::$conn)){
            try{
                static::$conn=new PDO('mysql:host='.static::$host.';port='.static::$port.';'.'dbname='.static::$database,static::$user,static::$password,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
                static::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                static::$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            }catch (PDOException $e){
                echo $e->getMessage();
            }
        }
    }

    /**
     * @param $param=[]
     * @param $bind=[]
     * @return []|bool
     */
    public static function selects($param,$bind=array())
    {
        if(is_array($param)&&!empty($param)&&is_array($bind)) {
            $sql = "select {$param['field']} from `{$param['table']}`";
            if(isset($param['where'])&&!empty($param['where'])){
                $sql.=" where {$param['where']}";
            }
            if (isset($param['order']) && !empty($param['order'])) {
                $sql .= " order by {$param['order']}";
            }
            if (isset($param['limit']) && !empty($param['limit'])) {
                $sql .= " limit {$param['limit']}";
            }
            if (isset($param['offset']) && !empty($param['offset'])) {
                $sql .= ",{$param['offset']}";
            }
            $sth = static::$conn->prepare($sql);
            $count=count($bind);
            for($i=0;$i<$count;$i++){
                if(is_integer($bind[$i])){
                    $sth->bindParam($i+1,$bind[$i],PDO::PARAM_INT);
                }
               elseif(is_string($bind[$i])){
                   $sth->bindParam($i+1,$bind[$i],PDO::PARAM_STR,strlen($bind[$i]));
                }
                else{
                    $sth->bindParam($i+1,$bind[$i]);
                }
            }
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    /**
     *  获得单条数据
     * @param $param=[] $bind=[]
     * @return []|bool
     */
    public static function getOne($param,$bind=array())
    {
        if(is_array($param)&&!empty($param)&&is_array($bind)) {
            $sql = "select {$param['field']} from `{$param['table']}`";
            if(isset($param['where'])&&!empty($param['where'])){
                $sql.=" where {$param['where']}";
            }
            $sth = static::$conn->prepare($sql);
            $count=count($bind);
            for($i=0;$i<$count;$i++){
                if(is_integer($bind[$i])){
                    $sth->bindParam($i+1,$bind[$i],PDO::PARAM_INT);
                }
                elseif(is_string($bind[$i])){
                    $sth->bindParam($i+1,$bind[$i],PDO::PARAM_STR,strlen($bind[$i]));
                }
                else{
                    $sth->bindParam($i+1,$bind[$i]);
                }
            }
            $sth->execute();
            return $sth->fetch(PDO::FETCH_ASSOC);
        }
    }

    /**
     * @param $param=[]
     * @param $bind=[]
     * 插入数据
     */
    public static function insert($param,$bind,$is_commit=false)
    {
        if(is_array($param)&&!empty($param)&&is_array($bind)&&!empty($bind)){
            $sql="insert {$param['table']} set {$param['set']}";
            $sth=static::$conn->prepare($sql);
            if($is_commit){
                static::begin();
            }
            $count=count($bind);
            for($i=0;$i<$count;$i++){
                if(is_integer($bind[$i])){
                    $sth->bindParam($i+1,$bind[$i],PDO::PARAM_INT);
                }
                elseif(is_string($bind[$i])){
                    $sth->bindParam($i+1,$bind[$i],PDO::PARAM_STR,strlen($bind[$i]));
                }
                else{
                    $sth->bindParam($i+1,$bind[$i]);
                }
            }
            return $sth->execute();
        }
    }

    /**
     * @param $param
     * @param $bind
     */
    public static function update($param,$bind,$is_commit=false)
    {
        $sql="update {$param['table']} set {$param['set']} where {$param['where']}";
        $sth=static::$conn->prepare($sql);
        if($is_commit){
            static::begin();
        }
        $count=count($bind);
        for($i=0;$i<$count;$i++){
            if(is_integer($bind[$i])){
                $sth->bindParam($i+1,$bind[$i],PDO::PARAM_INT);
            }
            elseif(is_string($bind[$i])){
                $sth->bindParam($i+1,$bind[$i],PDO::PARAM_STR,strlen($bind[$i]));
            }
            else{
                $sth->bindParam($i+1,$bind[$i]);
            }
        }
        return $sth->execute();
    }

    /**
     *
     */
    public static function begin()
    {
        static::$conn->beginTransaction();
    }

    /**
     *
     */
    public static function commit()
    {
        static::$conn->commit();
    }

    /**
     *
     */
    public static function rollBack()
    {
        static::$conn->rollBack();
    }
}
