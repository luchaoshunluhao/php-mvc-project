<?php
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\CommentModel;
final class CommentController extends BaseController
{
	public function index()
	{
		$this->denyAccess();
		$comments = CommentModel::getInstance()->fetchAllwithJoin();
		$this->smarty->assign("comments", $comments);
		$this->smarty->displays("index.html");
	}
	public function delete()
	{
		$this->denyAccess();
		$id = $_GET['id'];
		if(CommentModel::getInstance()->delete($id))
		{
			$this->jump("id={$id}的文章评论删除成功！", "?c=Comment");
		}
		else
		{
			$this->jump("id={$id}的文章评论删除失败！", "?c=Comment");
		}
	}
}