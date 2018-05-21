<?php
namespace Admin\Model;
use \Frame\Libs\BaseModel;
final class CategoryModel extends BaseModel
{
	protected $table = "category";
	/**
	 * [categoryList description]
	 * @param   $arrs  [原始的分类数据]
	 * @param   $level [菜单层级,初始值为0]
	 * @param   $pid   [上次递归传递过来的id值]
	 * @return  $categorys      [description]
	 */
	public function categoryList($arrs, $level = 0, $pid = 0)
	{
		//定义静态变量,用于存储每次递归找到的数据
		static $categorys = [];
		foreach ($arrs as $arr) 
		{
			//如果本次pid和传递过来的id相等的话,就找到了下层菜单
			if($arr['pid'] == $pid)
			{
				$arr['level'] = $level;//给数组添加level元素
				$categorys[] = $arr;
				//递归调用
				$this->categoryList($arrs, $level + 1, $arr['id']);
			}
		}
		//返回无限级分类的数组
		return $categorys;
	}
}