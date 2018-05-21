<?php
/**
 * Created by PhpStorm.
 * User: 路朝舜
 * Date: 2018/5/21
 * Time: 下午 07:28
 */
namespace Home\Model;
use Frame\Libs\BaseModel;
class CommentModel extends BaseModel
{
	protected $table = 'comment';
	public function fetchAllwithJoin( $where = '2>1' )
	{
		$sql  = "SELECT comment.*, user.`username` FROM {$this->table} ";
		$sql .= "LEFT JOIN user ON comment.user_id=user.id ";
		$sql .= "WHERE {$where} ORDER BY id DESC ";
		return $this->pdo->fetchAll($sql);
	}
	//获取评论的无限级分类数据
	public function commentList($arrs, $pid = 0)
	{
		$comments = array();
		foreach($arrs as $arr)
		{
			//先查找顶级评论
			if($arr['pid'] == $pid)
			{
				$arr['son'] = $this->commentList($arrs, $arr['id']);
				$comments[] = $arr;
			}
		}
		return $comments;
	}
	public function fetchThree($limit = '2>1')
	{
		$sql  = "SELECT comment.*, user.username FROM {$this->table} ";
		$sql .= "LEFT JOIN user ON comment.user_id=user.id ";
		$sql .= " ORDER BY id DESC $limit";
		return $this->pdo->fetchAll($sql);
	}
}