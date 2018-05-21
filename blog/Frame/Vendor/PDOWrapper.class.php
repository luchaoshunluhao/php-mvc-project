<?php
namespace Frame\Vendor;
use \PDO;
use \Exception;
use \PDOException;
final class PDOWrapper
{
    //私有的数据库配置信息
    private $db_type;
    private $db_host;
    private $db_port;
    private $db_user;
    private $db_pass;
    private $db_name;
    private $charset;
    private $pdo = null;
    //公共的构造方法
    public function __construct()
    {
        $this->db_type = $GLOBALS['config']['db_type'];
        $this->db_host = $GLOBALS['config']['db_host'];
        $this->db_port = $GLOBALS['config']['db_port'];
        $this->db_user = $GLOBALS['config']['db_user'];
        $this->db_pass = $GLOBALS['config']['db_pass'];
        $this->db_name = $GLOBALS['config']['db_name'];
        $this->charset = $GLOBALS['config']['charset'];
        $this->createPDO();     //创建PDO的对象
        $this->setErrorMode();  //设置PDO的报错模式
    }
    //私有的创建PDO类对象方法
    private function createPDO()
    {
        try
        {
            $dsn = "{$this->db_type}:host={$this->db_host};port={$this->db_port};";
            $dsn .= "dbname={$this->db_name};charset={$this->charset}";
            $this->pdo = new PDO($dsn, $this->db_user, $this->db_pass);
            //Exception异常基础类,不用创建PDO对象就能使用
        }
        catch(Exception $e)
        {
            echo "<h2>创建PDO类对象失败！</h2>";
            echo "错误编号：" . $e->getCode();
            echo "<br>错误行号：" . $e->getLine();
            echo "<br>错误信息：" . $e->getFile();
            echo "<br>错误信息：" . $e->getMessage();
        }
    }
    //私有的设置PDO报错模式
    private function setErrorMode()
    {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    //公共的执行非SELECT语句的方法
    public function exec($sql)
    {
        try
        {
            return $this->pdo->exec($sql);
        }
        catch(PDOException $e)
        {
            $this->showError($e);
        }
    }
    //获取单行数据
    public function fetchOne($sql)
    {
        try
        {
            $PDOStatement = $this->pdo->query($sql);
            return $PDOStatement->fetch(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {
            $this->showError($e);
        }
    }
    //获取多行数据
    public function fetchAll($sql)
    {
        try
        {
            $PDOStatement = $this->pdo->query($sql);
            return $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {
            $this->showError($e);
        }
    }
    //获取记录数
    public function rowCount($sql)
    {
        try
        {
            $PDOStatement = $this->pdo->query($sql);
            return $PDOStatement->rowCount();
        }
        catch(PDOException $e)
        {
            $this->showError($e);
        }
    }
    //私有的错误处理方法
    private function showError($e)
    {
        echo "<h2>执行SQL语句失败！</h2>";
        echo "错误编号：" . $e->getCode();
        echo "<br>错误行号：" . $e->getLine();
        echo "<br>错误信息：" . $e->getFile();
        echo "<br>错误信息：" . $e->getMessage();
    }
}