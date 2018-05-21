<?php
namespace Home\Model;
use \Frame\Libs\BaseModel;
final class ArticleModel extends BaseModel
{
	protected $table = "article";
	//获取文章按日期统计数据
	public function fetchAllwithCount()
	{
		$sql  = "SELECT date_format(from_unixtime(addate), '%Y年%m月') AS yearmonth, ";
		$sql .= "count(id) AS article_count FROM {$this->table} ";
		$sql .= "Group BY yearmonth ";
		$sql .= "ORDER BY yearmonth DESC";
		return $this->pdo->fetchAll($sql);
	}
	//获取文章连表查询的数据
	public function fetchAllwithJoin($where = '2>1', $stsrtrow, $pagesize)
	{
		$sql  = "SELECT article.*, category.classname, user.name FROM {$this->table} ";
		$sql .= "LEFT JOIN category ON article.category_id=category.id ";
		$sql .= "LEFT JOIN user ON article.user_id=user.id ";
		$sql .= "WHERE {$where} ";
		$sql .= "ORDER BY article.id DESC ";
		$sql .= "LIMIT {$stsrtrow}, {$pagesize}";
		return $this->pdo->fetchAll($sql);
	}
	//获取单行文章连表查询的数据
	public function fetchOnewithJoin($where = '2>1', $orderby = "article.id ASC")
	{
		$sql  = "SELECT article.*, category.classname, user.name FROM {$this->table} ";
		$sql .= "LEFT JOIN category ON article.category_id=category.id ";
		$sql .= "LEFT JOIN user ON article.user_id=user.id ";
		$sql .= "WHERE {$where} ";
		$sql .= "ORDER BY {$orderby} ";
		$sql .= "LIMIT 1";
		return $this->pdo->fetchOne($sql);
	}
	public function updateRead($id)
	{
		$sql = "UPDATE {$this->table} SET `read` = `read` + 1 WHERE id={$id}";
		return $this->pdo->exec($sql);
	}
	//更新点赞的方法
	public function updatePraise($id)
	{
		$sql = "UPDATE {$this->table} SET praise = praise + 1 WHERE id={$id}";
		return $this->pdo->exec($sql);
	}
	//更新文章评论数
	public function updateCommentCount($id)
	{
		$sql = "UPDATE {$this->table} SET `comment_count`=`comment_count`+1 WHERE id={$id}";
		return $this->pdo->exec($sql);
	}
}