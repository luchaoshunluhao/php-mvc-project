<?php
namespace Home\Controller;
use Admin\Controller\CommentController;
use \Frame\Libs\BaseController;
use \Home\Model\LinksModel;
use \Home\Model\CategoryModel;
use \Home\Model\ArticleModel;
use \Home\Model\CommentModel;

final class IndexController extends BaseController
{
    //首页方法
    public function index()
    {
    	//1.获取友情链接数据
        $links = LinksModel::getInstance()->fetchAll();
        //2.获取无限级分类+文章统计数据
        $categorys = CategoryModel::getInstance()->categoryList(
        		CategoryModel::getInstance()->fetchAllwithJoin()
        	);
        //3.获取按日期分类统计的数据
        $dates = ArticleModel::getInstance()->fetchAllwithCount();
        //4.构建查询条件
        $where = "2>1";
        if(isset($_REQUEST['title'])) $where .= " AND title LIKE '%" . $_REQUEST['title'] .  "%'";
        if(isset($_GET['category_id'])) $where .= " AND category_id=" . $_GET['category_id'];
        //5.构建分页参数
        $pagesize = 5;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $stsrtrow = ($page - 1) * $pagesize;
        $records = ArticleModel::getInstance()->rowCount($where);
        $params = array('c' => CONTROLLER, 'a' => ACTION);
        if(isset($_REQUEST['title'])) $params['title'] = $_REQUEST['title'];
        if(isset($_GET['category_id'])) $params['category_id'] = $_GET['category_id'];
        //6.创建分页字符串
        $pageObj = new \Frame\Vendor\Pager($records, $pagesize, $page, $params);
        $pageStr = $pageObj->showPage();
        //7.获取文章连表查询的数据
        $articles = ArticleModel::getInstance()->fetchAllwithJoin($where, $stsrtrow, $pagesize);
	    //8.获取最新评论查询的数据
	    $comment_s = CommentModel::getInstance()->fetchThree("LIMIT 3");
        //9.向视图赋值,并显示视图
        $this->smarty->assign(array(
        		'links' 	=> $links,
        		'categorys' => $categorys,
        		'dates'		=> $dates,
        		'articles'	=> $articles,
        		'pageStr'	=> $pageStr,
	            'comment_s' => $comment_s,
        	));
        $this->smarty->displays("index.html");
    }
    //显示文章列表方法
    public function showList()
    {
    	//1.获取友情链接数据
        $links = LinksModel::getInstance()->fetchAll();
        //2.获取无限级分类+文章统计数据
        $categorys = CategoryModel::getInstance()->categoryList(
        		CategoryModel::getInstance()->fetchAllwithJoin()
        	);
        //3.获取按日期分类统计的数据
        $dates = ArticleModel::getInstance()->fetchAllwithCount();
        //4.构建查询条件
        $where = "2>1";
        if(isset($_REQUEST['title'])) $where .= " AND title LIKE '%" . $_REQUEST['title'] .  "%'";
        if(isset($_GET['category_id'])) $where .= " AND category_id=" . $_GET['category_id']; 
        //5.构建分页参数
        $pagesize = 31;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $stsrtrow = ($page - 1) * $pagesize;
        $records = ArticleModel::getInstance()->rowCount($where);
        $params = array('c' => CONTROLLER, 'a' => ACTION);
        if(isset($_REQUEST['title'])) $params['title'] = $_REQUEST['title'];
        if(isset($_GET['category_id'])) $params['category_id'] = $_GET['category_id'];
        //6.创建分页字符串
        $pageObj = new \Frame\Vendor\Pager($records, $pagesize, $page, $params);
        $pageStr = $pageObj->showPage();
        //7.获取文章连表查询的数据
        $articles = ArticleModel::getInstance()->fetchAllwithJoin($where, $stsrtrow, $pagesize);
	    //8.获取最新评论查询的数据
	    $comment_s = CommentModel::getInstance()->fetchThree("LIMIT 3");
        //9.向视图赋值,并显示视图
        $this->smarty->assign(array(
        		'links' 	=> $links,
        		'categorys' => $categorys,
        		'dates'		=> $dates,
        		'articles'	=> $articles,
        		'pageStr'	=> $pageStr,
	            'comment_s' => $comment_s,
        	));
        $this->smarty->displays("list.html");
    }
    //显示文章内容的方法
    public function content()
    {
    	//获取地址栏传递的id
    	$id = $_GET['id'];
    	//1.获取友情链接数据
        $links = LinksModel::getInstance()->fetchAll();
        //2.获取无限级分类+文章统计数据
        $categorys = CategoryModel::getInstance()->categoryList(
        		CategoryModel::getInstance()->fetchAllwithJoin()
        	);
        //3.获取按日期分类统计的数据
        $dates = ArticleModel::getInstance()->fetchAllwithCount();
        //4.构建查询条件
        $where = "2>1";
        if(isset($_REQUEST['title'])) $where .= " AND title LIKE '%" . $_REQUEST['title'] .  "%'";
        if(isset($_GET['category_id'])) $where .= " AND category_id=" . $_GET['category_id']; 

    	//更新文章阅读数
    	ArticleModel::getInstance()->updateRead($id);
    	//获取单条连表查询的数据
    	$article = ArticleModel::getInstance()->fetchOnewithJoin("article.id=$id", "article.id ASC");

    	//获取前一篇和后一篇文章数据
    	$prevnext[] = ArticleModel::getInstance()->fetchOnewithJoin("article.id<$id", "article.id DESC");
    	$prevnext[] = ArticleModel::getInstance()->fetchOnewithJoin("article.id>$id", "article.id ASC");
    	//获取评论的数据
	    $comments = CommentModel::getInstance()->commentList(
	    	    CommentModel::getInstance()->fetchAllwithJoin("article_id=$id")
	        );
	    //获取最新评论查询的数据
		$comment_s = CommentModel::getInstance()->fetchThree("LIMIT 3");

	    $this->smarty->assign(array(
        		'links' 	=> $links,
        		'categorys' => $categorys,
        		'dates'		=> $dates,
        		'article'	=> $article,
        		'prevnext'	=> $prevnext,
		        'comments'  => $comments,
		        'comment_s'  => $comment_s,
        	));
    	$this->smarty->displays("content.html");
    }
    //用户点赞
    public function praise()
    {
    	$id = $_GET['id'];
    	//判断用户是否登录,只有登录用户才能点赞
    	if (!empty($_SESSION['username'])) 
    	{
    		//判断该文章是否点赞过
    		//需要记录文章点赞过的状态,可以用数组来存储点赞状态
    		//$_SESSION[praise]['1']=true 点过了
    		//$_SESSION[praise]['2']=true 没点过
    		if (empty($_SESSION['praise'][$id])) 
    		{
    			//更新点赞数
    			ArticleModel::getInstance()->updatePraise($id);
    			//将文章的点赞状态存入到session中
    			$_SESSION['praise'][$id] = true;
    			$this->jump("点赞成功！", "?c=Index&a=content&id=$id");
    		}
    		else
    		{
    			//同一篇文章不能重复点赞
    			$this->jump("同一篇文章不能重复点赞！", "?c=Index&a=content&id=$id");
    		}
    	}
    	else
    	{
    		//如果没有登录,这跳转到登录页面
    		$this->jump("只有登录才能点赞！", "./admin.php?c=User&a=login");
    	}
    }
    //评论的方法
    public function send()
    {
	    //权限验证
	    $this->denyAccess();
        $data['user_id']    = $_SESSION['uid'];
	    $data['article_id'] = $_POST['article_id'];
        $data['pid']        = $_POST['pid'];
	    $data['content']    = $_POST['content'];
	    $data['addate']     = time();
	    if(CommentModel::getInstance()->insert($data))
	    {
		    $this->jump("评论发布成功！", "?c=Index&a=content&id=" . $data['article_id']);
	    }
	    else
	    {
		    $this->jump("评论发布失败！", "?c=Index&a=content&id=" . $data['article_id']);
	    }
    }
}