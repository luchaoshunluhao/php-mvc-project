<?php
//定义常量

define("DS", DIRECTORY_SEPARATOR);
define("ROOT_PATH", getcwd() . DS);
define("APP_PATH", ROOT_PATH . "Admin" . DS);//平台(应用)目录
require_once(ROOT_PATH . "Frame" . DS . "Frame.class.php");
//调用框架初始化方法
\Frame\Frame::run();