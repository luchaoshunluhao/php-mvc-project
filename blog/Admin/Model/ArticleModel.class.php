<?php
namespace Admin\Model;
use \Frame\Libs\BaseModel;
final class ArticleModel extends BaseModel
{
	protected $table = "article";
	//获取连表查询的文章数据
	public function fetchAllwithJoin($where = '2>1', $startrow = 0, $pagesize = 10)
	{
		$sql = "SELECT article.*, category.classname, user.username FROM `{$this->table}` ";
		$sql .= "LEFT JOIN category ON article.`category_id`=category.`id` ";
		$sql .= "LEFT JOIN user ON article.`user_id`=user.`id`";
		$sql .= "WHERE {$where} ";
		$sql .= "ORDER BY article.`orderby` ASC, article.`id` DESC ";
		$sql .= "LIMIT {$startrow}, {$pagesize}";
		return $this->pdo->fetchAll($sql);
	}
}