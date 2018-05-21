<?php
//声明命名空间
namespace Frame\Vendor;

//定义最终的图像验证码类
final class Captcha
{
	//私有的成员属性
	private $code; 		//验证码字符串
	private $width; 	//画布宽度
	private $height; 	//画布高度
	private $fontsize;	//字号大小
	private $fontfile;	//字体文件
	private $img;   	//画布资源

	//公共的构造方法：验证码初始化
	public function __construct($width = 85,$height = 24,$fontsize = 20)
	{
		$this->width = $width;
		$this->height = $height;
		$this->fontsize = $fontsize;
		$this->fontfile = "./Public/Admin/Images/msyh.ttf";
		$this->createCode(); //创建验证码字符串
		$this->createImg(); //创建画布
		$this->createBg(); //绘制画布
		$this->createFont(); //写入字符串
		$this->outPut(); //输出图像
	}

	//私有的创建验证码随机字符串
	private function createCode()
	{
		//产生一个随机字符串的数组
		$arr = array_merge(range('a', 'z'),range(0, 9),range('A', 'Z'));
		//打乱数组的顺序
		shuffle($arr);
		shuffle($arr);
		//从原数组中，随机取四个下标
		$arr2 = array_rand($arr, 4);
		//根据随机下标取出对应的数组元素的值
		$str = "";
		foreach($arr2 as $index)
		{
			$str .= $arr[$index];
		}
		//将随机字符串保存到$code属性中
		$this->code = $str;
	}

	//私有的创建画布的方法
	private function createImg()
	{
		//创建真彩色的空画布
		$this->img = imagecreatetruecolor($this->width, $this->height);
	}

	//私有的绘制画布的方法
	private function createBg()
	{
		//分配颜色
		$color = imagecolorallocate($this->img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 200));
		//绘制带背景的矩形
		imagefilledrectangle($this->img, 0, 0, $this->width, $this->height, $color);
		//添加像素点
		for($i=0; $i < 100; $i++)
		{
			$color = imagecolorallocate($this->img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 200));
			imagesetpixel($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
		}
	}

	//私有的写入随机字符串的方法
	private function createFont()
	{
		//分配颜色
		$color = imagecolorallocate($this->img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 200));
		//写入字符串
		imagettftext($this->img, $this->fontsize, 0, 8, 22, $color, $this->fontfile, $this->code);
	}

	//私有的输出图像的方法
	private function outPut()
	{
		header("Content-Type:image/png");
		imagepng($this->img);
		imagedestroy($this->img);
	}

	//返回验证码字符串
	public function getCode()
	{
		return strtolower($this->code);
	}
}