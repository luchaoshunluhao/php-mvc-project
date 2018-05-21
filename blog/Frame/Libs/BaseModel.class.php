<?php
namespace Frame\Libs;
use \Frame\Vendor\PDOWrapper;
abstract class BaseModel
{
    //受保护的保存PDO对象属性
    protected $pdo = null;
    //私有的静态的保存模型类对象的数组属性
    private static $arrModelObj = [];
    //公共的构造方法
    public function __construct()
    {
        //创建PDOWrapper类的对象
        $this->pdo = new PDOWrapper();
    }
    public static function getInstance()
    {
        //获取静态方式调用的类名,即后期静态延时绑定
        $className = get_called_class();
        
        if(empty(self::$arrModelObj[$className]))
        {
            //例如:$className = "\Home\Model\StudentModel";
            self::$arrModelObj[$className] = new $className();
        }
        return self::$arrModelObj[$className];
    }

    
    public function fetchOne($where = '2>1')
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$where} LIMIT 1";
        return $this->pdo->fetchOne($sql);
    }
    public function update($data, $id)
    {
        $str = "";
        foreach($data as $key => $value)
        {
            $str .= "$key='$value',";
        }
        $str = rtrim($str, ",");
        $sql = "UPDATE {$this->table} SET {$str} WHERE id={$id}";
        return $this->pdo->exec($sql);
    }
    public function fetchAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id";
        return $this->pdo->fetchAll($sql);
    }
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id={$id}";
        return $this->pdo->exec($sql);
    }
    public function rowCount($where = "2>1")
    {
        //构建查询的sql语句
        $sql = "SELECT * FROM {$this->table} WHERE {$where}";
        //执行sql语句,并返回结果(整数)
        return $this->pdo->rowCount($sql);
    }
    public function insert($data)
    {
        $fields = "";
        $values = "";
        foreach ($data as $key => $value) 
        {
            $fields .= "$key,";
            $values .= "'$value',";
        }
        $fields = rtrim($fields, ",");
        $values = rtrim($values, ",");
        $sql = "INSERT INTO {$this->table}({$fields}) VALUES({$values})";
        return $this->pdo->exec($sql);
    }
}