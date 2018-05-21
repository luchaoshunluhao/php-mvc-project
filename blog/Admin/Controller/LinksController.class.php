<?php
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\LinksModel;
final class LinksController extends BaseController
{
	public function index()
	{
		$links = LinksModel::getInstance()->fetchAll();
		$this->smarty->assign("links", $links);
		$this->smarty->displays("index.html");
	}
	public function delete()
	{
		$id = $_GET['id'];
		if (LinksModel::getInstance()->delete($id)) {
			$this->jump("id={$id}的记录删除成功！", "?c=Links");
		}
		else
		{
			$this->jump("id={$id}的记录删除失败！", "?c=Links");
		}
	}
	public function add()
	{
		$this->smarty->displays("add.html");
	}
	public function insert()
	{
		$data['domain'] = $_POST['domain'];
		$data['url'] = $_POST['url'];
		$data['orderby'] = $_POST['orderby'];
		if(LinksModel::getInstance()->insert($data))
		{
			$this->jump("添加成功！", "?c=Links");
		}
		else
		{
			$this->jump("添加失败！", "?c=Links&a=add");
		}
	}
}