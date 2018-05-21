<?php
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\UserModel;

final class UserController extends BaseController
{
	public function index()
	{
		//权限验证
		$this->denyAccess();
		//获取多行数据
		$users = UserModel::getInstance()->fetchAll();
		//向视图赋值,并显示视图
		$this->smarty->assign("users", $users);
		$this->smarty->displays("index.html");
	}
	public function delete()
	{
		//权限验证
		$this->denyAccess();
		$id = $_GET['id'];
		if(UserModel::getInstance()->delete($id))
		{
			$this->jump("id={$id}的记录删除成功！", "?c=User");
		}
		else
		{
			$this->jump("id={$id}的记录删除失败！", "?c=User");
		}
	}
	public function add()
	{
		//权限验证
		$this->denyAccess();
		$this->smarty->displays("add.html");
	}
	public function insert()
	{
		//权限验证
		$this->denyAccess();
		$data['username'] = $_POST['username'];
		$data['password'] = md5($_POST['password']);
		$data['name'] 	  = $_POST['name'];
		$data['tel'] 	  = $_POST['tel'];
		$data['status']   = $_POST['status'];
		$data['role']     = $_POST['role'];
		$data['addate']   = time();
		//用户是否已经存在
		if(UserModel::getInstance()->rowCount("username='{$data['username']}'"))
		{
			$this->jump("用户名{$data['username']}已经被注册！", "?c=User&a=add");		
		}
		if(UserModel::getInstance()->insert($data))
		{
			$this->jump("用户注册成功！", "?c=User");
		}
		else
		{
			$this->jump("用户注册失败！", "?c=User&a=add");
		}
	}
	public function edit()
	{
		//权限验证
		$this->denyAccess();
		$id = $_GET['id'];
		$user = UserModel::getInstance()->fetchOne("id={$id}");
		$this->smarty->assign("user", $user);
		$this->smarty->displays("edit.html");

	}
	public function update()
	{
		//权限验证
		$this->denyAccess();
		$id = $_POST['id'];
		$data['name'] 	  = $_POST['name'];
		$data['tel'] 	  = $_POST['tel'];
		$data['status']   = $_POST['status'];
		$data['role']     = $_POST['role'];
		//如果密码不为空,则保存密码
		if (!empty($_POST['password']) && !empty($_POST['confirmpwd'])) 
		{
			//判断两次密码是否一致
			if ($_POST['password'] == $_POST['confirmpwd']) 
			{
				$data['password'] = md5($_POST['password']);
			}
			else
			{
				$this->jump("两次输入的密码不一致！", "?c=User&a=edit&id={$id}");
			}
		}
		if(UserModel::getInstance()->update($data, $id))
		{
			$this->jump("用户信息修改成功！", "?c=User");
		}
		else
		{
			$this->jump("用户信息修改失败！", "?c=User&a=edit&id={$id}");
		}
	}
	public function login()
	{
		$this->smarty->displays("login.html");
	}
	public function loginCheck()
	{
		$username = $_POST['username'];
		$password = md5($_POST['password']);
		$verify   = strtolower($_POST['verify']); 

		//判断验证码是否一致
		if($verify != $_SESSION['captcha'])
		{
			$this->jump("验证码不一致！", "?c=User&a=login");
		}
		//判断用户名和密码是否与数据库一致
		$user = UserModel::getInstance()->fetchOne("username='{$username}' and password='{$password}'");
		if(!$user)
		{
			$this->jump("用户名或密码不正确！", "?c=User&a=login");
		}
		//更新用户资源：最后登录ip，最后登录时间，登录次数+1
		$data['last_login_ip']   = $_SERVER['REMOTE_ADDR'];
		$data['last_login_time'] = time();
		$data['login_times']     = $user['login_times'] + 1;
		UserModel::getInstance()->update($data, $user['id']);
		//将用户的状态信息存入session
		$_SESSION['uid'] = $user['id'];
		$_SESSION['username'] = $username;
		//跳转到后台首页
		$this->jump("用户{$username}成功登录,正在跳转...", "./admin.php");
	}
	//验证码方法
	public function captcha()
	{
		//创建验证码类的对象
		$captcha = new \Frame\Vendor\Captcha();
		//将验证码字符串保存在session中
		$_SESSION['captcha'] = $captcha->getCode();
	}
	//用户退出的方法
	public function logout()
	{
		//删除session数据
		unset($_SESSION['username']);
		unset($_SESSION['uid']);
		//删除session文件
		session_destroy();
		//跳转到登录页面
		$this->jump("用户退出成功！", "?c=User&a=login");
	}
}