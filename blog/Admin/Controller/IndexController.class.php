<?php
namespace Admin\Controller;
use \Frame\Libs\BaseController;
final class IndexController extends BaseController
{
    public function index()
    {
        //权限验证
        $this->denyAccess();
        $this->smarty->displays('index.html');
    }
    public function top()
    {
        //权限验证
        $this->denyAccess();
        $this->smarty->displays('top.html');
    }
    public function left()
    {
        //权限验证
        $this->denyAccess();
        $this->smarty->displays('left.html');
    }
    public function center()
    {
        //权限验证
        $this->denyAccess();
        $this->smarty->displays('center.html');
    }
    public function main()
    {
        //权限验证
        $this->denyAccess();
        $this->smarty->displays('main.html');
    }
}