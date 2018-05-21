<?php
namespace Frame\Libs;
//定义最终的单例的数据库工具类
final class Db
{
    //私有的静态的保存对象的属性
    private static $db = NULL;

    //私有的数据库配置信息
    private $db_host;
    private $db_port;
    private $db_user;
    private $db_pass;
    private $db_name;
    private $charset;

    //私有的构造方法
    private function __construct()
    {
        $this->db_host  = $GLOBALS['config']['db_host'];
        $this->db_port  = $GLOBALS['config']['db_port'];
        $this->db_user  = $GLOBALS['config']['db_user'];
        $this->db_pass  = $GLOBALS['config']['db_pass'];
        $this->db_name  = $GLOBALS['config']['db_name'];
        $this->charset  = $GLOBALS['config']['charset'];

        $this->connect();
        $this->select();
        $this->charset();
    }
    //私有的克隆方法
    private function __clone(){}
    
    //公共的静态的创建对象的方法
    public static function getInstance()
    {
        //判断当前对象是否存在
        if(empty(self::$db))
        {
            //如果当前类的对象不存在，则创建保存
            self::$db = new self();
        }
        //返回当前类的对象
        return self::$db;
    }
    //私有的连接数据库
    private function connect()
    {
        if( !@mysql_connect("{$this->db_host}:{$this->db_port}", $this->db_user, $this->db_pass) )
          die("数据库连接失败！");
    }
    //私有的选择数据库
    private function select()
    {
        if( !mysql_select_db($this->db_name) )
          die("数据库{$this->db_name}选择失败！");
    }
    //私有的选择字符集
    private function charset()
    {
        $this->exec("set names $this->charset");
    }
    //公共的执行sql语句的方法：insert等
    public function exec($sql)
    {
        //将sql语句转成全小写
        $sql = strtolower($sql);
        //判断sql语句是不是select语句
        if(substr($sql, 0, 6) == 'select')
        {
            die("exec方法不能执行select语句！");
        }
        //执行sql语句，并返回布尔值
        return mysql_query($sql);
    }
    //私有的执行sql语句的方法：select
    //该方法返回结果是结果集资源
    private function query($sql)
    {
        //将sql语句转成全小写
        $sql = strtolower($sql);
        //判断sql语句是不是select语句
        if(substr($sql, 0, 6) != 'select')
        {
            die("query方法不能执行非select语句！");
        }
        //执行sql语句，并返回结果及资源
        return mysql_query($sql);
    }
    //获取单行数据即返回一维数组
    public function fetchOne($sql, $type = 3)
    {
        //执行sql语句并返回结果集
        $res = $this->query($sql);
        //数值和常量的对应关系
        $types = array(
          1 => MYSQL_NUM,
          2 => MYSQL_BOTH,
          3 => MYSQL_ASSOC
        );
        //从结果集获取一行数据，并返回
        return mysql_fetch_array($res, $types[$type]);
    }
    //获取多行数据即返回二维数组
    public function fetchAll($sql, $type = 3)
    {
        //执行sql语句并返回结果集
        $res = $this->query($sql);
        //数值和常量的对应关系
        $types = array(
          1 => MYSQL_NUM,
          2 => MYSQL_BOTH,
          3 => MYSQL_ASSOC
        );
        //循环从结果集获取所有行数据，并返回
        while($row = mysql_fetch_array($res, $types[$type]))
        {
            $arrs[] = $row;
        }
        //返回二维数组
        return $arrs;
    }
    //获取记录数
    public function rowCount($sql)
    {
        //执行sql语句，并返回结果集
        $res = $this->query($sql);
        //返回记录数
        return mysql_num_rows($res);
    }
    //析构方法
    public function __destruct()
    {
        mysql_close();
    }
}