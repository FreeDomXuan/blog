<?php

namespace controller;
use \vendor\Page;
use \vendor\MySendMail;
use \model\ArticleModel;
use \model\MessageModel;
use \vendor\Code;

session_start();

class Blog1Controller extends Controller
{
	function blog1()
	{
		$wen = new ArticleModel();
		$con = $wen->where('type=0')->count('bid');
		$page = new Page(4,$con);

		if (empty($_GET['date'])) {
			$data = $wen->where('type=0')->limit($page->limit())->select();	
		} else {
			$data = $wen->getDateBlog($_GET['date'],$page->limit());
		}

		$title = '文章列表 - 问君随风';
		//获取全部分页URL
		$pa = $page->allPage();
		//获取推荐文章信息
		$pops = $wen->getPopBlog();
		//获取月份归档
		$dateMY = $wen->getBlogDate();
		//分配变量，展示
		$this->assign('dateMY',$dateMY);
		$this->assign('pops',$pops);
		$this->assign('pages',$pa);
		$this->assign('title', $title);
		$this->assign('data', $data); 
		$this->display();
	}

	function msg()
	{
		if (empty($_POST['email']) && empty($_POST['yzm']) && empty($_POST['name']) && empty($_POST['msg']) ) {
			echo "<script language='javascript'>alert('请填写完整信息');history.back();</script>";
			exit;
		} 

		if (strcasecmp($_POST['yzm'], $_SESSION['yzm'])) {
			echo "<script language='javascript'>alert('验证码错误，请重新输入');history.back();</script>";
			exit;
		}

		if($_SERVER['REMOTE_ADDR'] == '::1'){
			$ip = ip2long('127.0.0.1');
		}else{
			$ip = ip2long($_SERVER['REMOTE_ADDR']);
		}

		$data = [
			'message' => $_POST['msg'],
			'username' => $_POST['name'],
			'email' => $_POST['email'],
			'addtime' => time(),
			'addip' => $ip,
			'msg' => $_POST['msg'],
		];
		

		$msg = new MessageModel();
		$msg->insert($data);

		$mail = new MySendMail();
		$mail->setServer("smtp.126.com", "FreeDomXuan@126.com", "xuan6520685");
		$mail->setFrom("FreeDomXuan@126.com");
		$mail->setReceiver($_POST['email']);
		$mail->setMailInfo("您好", "您的留言我们已经收到，我们会尽快进行处理并与您联系，感谢来访");
		$mail->sendMail();

		header('Location: index.php?m=blog1&a=blog1');
	}
}