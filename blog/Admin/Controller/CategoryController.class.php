<?php
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\CategoryModel;
final class CategoryController extends BaseController
{
	public function index()
	{
		$this->denyAccess();
		//获取初始的分类数据
		$categorys = CategoryModel::getInstance()->fetchAll();
		//获取无限级分类数据
		$categorys = CategoryModel::getInstance()->categoryList($categorys);
		$this->smarty->assign("categorys", $categorys);
		$this->smarty->displays("index.html");
	}
	public function add()
	{
		$this->denyAccess();
		//获取无限级分类数据
		$categorys = CategoryModel::getInstance()->categoryList(CategoryModel::getInstance()->fetchAll());
		$this->smarty->assign("categorys", $categorys);
		$this->smarty->displays("add.html");
	}
	public function insert()
	{
		$this->denyAccess();
		$data['classname'] = $_POST['classname'];
		$data['orderby'] = $_POST['orderby'];
		$data['pid'] = $_POST['pid'];
		if(CategoryModel::getInstance()->insert($data))
		{
			$this->jump("分类数据添加成功！", "?c=Category");
		}
		else
		{
			$this->jump("分类数据添加失败！", "?c=Category&a=add");
		}

	}
	public function edit()
	{
		$this->denyAccess();
		$id = $_GET['id'];
		$category = CategoryModel::getInstance()->fetchOne("`id`={$id}");
		//获取无限级分类数据
		$categorys = CategoryModel::getInstance()->categoryList(CategoryModel::getInstance()->fetchAll());
		$this->smarty->assign("categorys", $categorys);
		$this->smarty->assign("category", $category);
		$this->smarty->displays("edit.html");
	}
	public function update()
	{
		$id = $_POST['id'];
		$data['classname'] = $_POST['classname'];
		$data['orderby'] = $_POST['orderby'];
		$data['pid'] = $_POST['pid'];
		if(CategoryModel::getInstance()->update($data, $id))
		{
			$this->jump("分类数据修改成功！", "?c=Category");
		}
		else
		{
			$this->jump("分类数据修改失败！", "?c=Category&a=edit&id={$id}");
		}
	}
	public function delete()
	{
		$this->denyAccess();
		$id = $_GET['id'];
		if(CategoryModel::getInstance()->delete($id))
		{
			$this->jump("id={$id}的分类名称删除成功！", "?c=Category");
		}
		else
		{
			$this->jump("id={$id}的分类名称删除失败！", "?c=Category&a=add");
		}
	}
}