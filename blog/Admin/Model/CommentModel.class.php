<?php
namespace Admin\Model;
use \Frame\Libs\BaseModel;
final class CommentModel extends BaseModel
{
	protected $table = "comment";
	public function fetchAllwithJoin()
	{
		$sql = "SELECT comment.`id`, user.`username`, comment.`content`, article.`title`, comment.`addate`, a.content AS parent_content FROM {$this->table} ";
		$sql .= "LEFT JOIN user ON comment.user_id=user.id ";
		$sql .= "LEFT JOIN article ON comment.article_id=article.id ";
		$sql .= "LEFT JOIN comment AS a ON a.id=comment.pid";
		return $this->pdo->fetchAll($sql);
	}
}