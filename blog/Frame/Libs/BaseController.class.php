<?php
namespace Frame\Libs;
use \Frame\Vendor\Smarty;

abstract class BaseController
{
    //受保护的$smarty对象属性
    protected $smarty = null;
    public function __construct()
    {
        //创建Samrty类的对象
        $smarty = new Smarty();
        //Smarty配置
        $smarty->left_delimiter = "<{";
        $smarty->right_delimiter = "}>";
        $smarty->setTemplateDir(VIEW_PATH);
        $smarty->setCompileDir(sys_get_temp_dir() . DS . "c" . DS);
        //将$smarty变量赋值给$smarty属性
        $this->smarty = $smarty;
    }
    protected function jump($message, $url = '?', $time = 3)
    {
        //向视图赋值,并显示视图
        $this->smarty->assign("message", $message);
        $this->smarty->assign("url", $url);
        $this->smarty->assign("time", $time);
        $this->smarty->displays("jump.html", 2);
        die();
    }
    //用户的访问权限验证方法
    protected function denyAccess()
    {
        //判断用户是否登录
        if(empty($_SESSION['username']))
        {
            $this->jump("你还没有登录,请先登录", "?c=User&a=lOGIN");
        }
    }
}