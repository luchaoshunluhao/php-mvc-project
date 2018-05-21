<?php
namespace Frame\Vendor;
//包含原始的Smarty类:./Frame/Vendor/Smarty/libs/Smarty.class.php
require_once(FRAME_PATH . "Vendor" . DS . "Smarty" . DS . "libs" . DS . "Smarty.class.php");

//定义最终的Smarty类,并继承原始的Smarty类
final class Smarty extends \Smarty
{
	public function displays($message, $type = 1)
	{
		if ($type == 2) 
		{
			$type = VIEW_PATH . 'Public' . DS;
			$message = $type . $message;
			$this->display($message);
		}
		elseif($type == 1)
		{
			$type = VIEW_PATH . CONTROLLER . DS;
			$message = $type . $message;
			$this->display($message);
		}

	}
}