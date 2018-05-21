<?php
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\ArticleModel;
use \Admin\Model\CategoryModel;
final class ArticleController extends BaseController
{
	public function index()
	{
        //权限验证
        $this->denyAccess();
		//获取无限级分类数据
		$categorys = CategoryModel::getInstance()->categoryList(CategoryModel::getInstance()->fetchAll());
		//获取搜索条件
		$where = "2>1";
		if(!empty($_REQUEST['category_id'])) $where .= " AND category_id=" . $_REQUEST['category_id']; 
		if(!empty($_REQUEST['keyword'])) $where .= " AND title LIKE '%" . $_REQUEST['keyword'] . "%'";
		//构建分页参数
		$pagesize = 5;
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$startrow = ($page - 1) * $pagesize;
		$records = ArticleModel::getInstance()->rowCount($where);//记录数
		$params = array('c' => CONTROLLER, 'a' => ACTION);
		if(!empty($_REQUEST['category_id'])) $params['category_id'] = $_REQUEST['category_id']; 
		if(!empty($_REQUEST['keyword'])) $params['keyword'] .= $_REQUEST['keyword'];


		//获取连表查询的文章数据
		$articles = ArticleModel::getInstance()->fetchAllwithJoin($where, $startrow, $pagesize);
		//创建分页类对象
		$pageObj = new \Frame\Vendor\Pager($records, $pagesize, $page, $params);
		$pageStr = $pageObj->showPage();

		$this->smarty->assign(array(
				'categorys' => $categorys,
				'articles'  => $articles,
				'pageStr'	=> $pageStr
			));
		$this->smarty->displays("index.html");
	}
	public function add()
	{
        //权限验证
        $this->denyAccess();
		$categorys = CategoryModel::getInstance()->categoryList(CategoryModel::getInstance()->fetchAll());
		$this->smarty->assign("categorys", $categorys);
		$this->smarty->displays("add.html");
	}
	public function insert()
	{
        //权限验证
        $this->denyAccess();
		$data['category_id'] = $_POST['category_id'];
		$data['user_id']	 = $_SESSION['uid'];	
		$data['title'] 		 = $_POST['title'];
		$data['content'] 	 = $_POST['content'];
		$data['top'] 		 = isset($_POST['top']) ? 1 : 0;
		$data['orderby']	 = $_POST['orderby'];
		$data['addate']	 	 = time();
		if(ArticleModel::getInstance()->insert($data))
		{
			$this->jump("文章发布成功！", "?c=Article");
		}
		else
		{
			$this->jump("文章发布失败！", "?c=Article&a=add");
		}

	}
	public function edit()
	{
		$this->denyAccess();
		$id = $_GET['id'];
		$article = ArticleModel::getInstance()->fetchOne("id=$id");
		//获取无限级分类数据
		$categorys = CategoryModel::getInstance()->categoryList(CategoryModel::getInstance()->fetchAll());
		$this->smarty->assign('article', $article);
		$this->smarty->assign('categorys', $categorys);
		$this->smarty->displays("edit.html");
	}
	public function update()
	{
		$this->denyAccess();
		$id = $_POST['id'];
		$data['category_id'] = $_POST['category_id'];
		$data['title'] = $_POST['title'];
		$data['orderby'] = $_POST['orderby'];
		$data['top'] = isset($_POST['top']) ? 1 : 0;
		$data['content'] = $_POST['content'];
		if(ArticleModel::getInstance()->update($data, $id))
		{
			$this->jump("文章修改成功！", "?c=Article");
		}
		else
		{
			$this->jump( "文章修改失败！", "?c=Article&a=edit&id={$id}" );
		}
	}
}