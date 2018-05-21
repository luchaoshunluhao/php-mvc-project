<?php
//声明命名空间
namespace Frame;

final class Frame
{
    //公共的静态的框架初始化方法
    public static function run()
    {
        self::initcharset();
        self::initConfig();
        self::initRoute();
        self::initConst();
        self::initAutoLoad();
        self::initDispatch();
    }
    private static function initcharset()
    {
        header('Content-Type:text/html;charset=utf-8');
        //开启session会话
        session_start();
    }
    private static function initConfig()
    {
        $GLOBALS['config'] = require_once(APP_PATH . "Conf" . DS . "Config.php");
    }
    private static function initRoute()
    {
        $p = $GLOBALS['config']['default_platform'];
        $c = isset($_GET['c']) ? $_GET['c'] : $GLOBALS['config']['default_controller'];
        $a = isset($_GET['a']) ? $_GET['a'] : $GLOBALS['config']['default_action'];
        define('PLAT', $p);
        define('CONTROLLER', $c);
        define('ACTION', $a);
    }
    private static function initConst()
    {
        define("FRAME_PATH", ROOT_PATH . "Frame" . DS);
        define("VIEW_PATH", APP_PATH . "View" . DS);
    }
    private static function initAutoLoad()
    {
        spl_autoload_register(function($className){
            //将"空间+类名"转成真实的类文件路径
            $filename = ROOT_PATH . str_replace('\\', DS, $className) . '.class.php';
            //如果类文件存在,则包含
            if(file_exists($filename))  require_once($filename);
        });
    }
    private static function initDispatch()
    {
        $controllerClassName = "\\" . PLAT . "\\" . "Controller" . "\\" . CONTROLLER . "Controller";
        $controllerObj = new $controllerClassName();
        //构建动态用户动作名称
        $actionName = ACTION;
        $controllerObj->$actionName();

    }
}