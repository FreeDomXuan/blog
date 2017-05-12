<?php

namespace controller;
use \vendor\Code;
use \vendor\Page;
use \vendor\Upload;
use \model\ArticleModel;
use \model\MessageModel;
use \model\PicModel;
use \model\LinkModel;
use \model\ReplyModel;
use \model\MeModel;

session_start();

class AdminController extends Controller
{
	function admin()
	{
		$this->owner();
		$this->display('index.html');
	}

	function addpic()
	{
		$this->owner();
		$upimg = new Upload();
		$addpic = new PicModel();
		if (!empty($_POST['add'])) {
			$pic = $upimg->uploadFile('icon');
			$data = [
			'title' => $_POST['title'],
			'photo' => $pic,
			'introduce' => $_POST['note'],
			'type' => $_POST['type'],
			'says' => $_POST['says'],
			'addtime' => time(),
			];
			$addpic->insert($data);
		}
		$this->display('addpic.html');
	}

	function add()
	{
		$this->owner();
		$arc = new ArticleModel();
		$upimg = new Upload();
		if (!empty($_POST['add'])) {
			$pic = $upimg->uploadFile('icon');
			$data = [
			'title' => $_POST['title'],
			'posttime' => time(),
			'content' => htmlspecialchars($_POST['test-editormd-markdown-doc']),
			'pic' => $pic,
			'intro' => $_POST['note'],
			'type' => $_POST['type']
			];
			$arc->insert($data);
		}
		$this->display('add.html');
	}

	function adv()
	{
		$this->owner();
		$link = new LinkModel();
		$upimg = new Upload();

		if (!empty($_POST['way'])) {
			$pic = $upimg->uploadFile('icon');
		}

		if (!empty($_GET['del'])) {
			$link->where("lid=" . $_GET['del'])->delete();
		}
		
		if (!empty($_GET['change'])) {
			$thisLink = $link->where("lid=" . $_GET['change'])->select();
			$thisLink = $thisLink[0];
			$this->assign('thisLink',$thisLink);
		}

		if (!empty($_POST['add'])) {
			if (empty($pic)) {
				$data = [
				'linkname' => $_POST['title'],
				'url' => $_POST['url'],
				'description' => $_POST['note'],
				'displayorder' => $_POST['sort'],
				'addtime' => time(),
				];
			} else {
				$data = [
				'linkname' => $_POST['title'],
				'url' => $_POST['url'],
				'description' => $_POST['note'],
				'displayorder' => $_POST['sort'],
				'logo' => $pic,
				'addtime' => time(),
				];
			}
			

			$link->insert($data);
			echo $link->sql();
		}

		if (!empty($_POST['change'])) {
			$data = [
				'linkname' => $_POST['title'],
				'url' => $_POST['url'],
				'description' => $_POST['note'],
				'displayorder' => $_POST['sort'],
				'addtime' => time(),
			];
			$link->where('lid='.$_GET['change'])->update($data);

			if (!empty($pic)) {
				$img = ['logo'=> $pic];
				$link->where('lid='.$_GET['change'])->update($img);
			}
		}

		$links = $link->select();

		$this->assign('links',$links);
		$this->display('adv.html');
	}

	function list()
	{
		// var_dump($_GET,$_POST);
		$this->owner();
		$arc = new ArticleModel();
		$page = new Page();
		$con = $arc->count('bid');
		$page = new Page(5,$con);
		$pa = $page->allPage();
		$data = $arc->limit($page->limit())->select();

		if (!empty($_GET['del'])) {
			$arc->where("bid=" . $_GET['del'])->delete();
		}
		if (!empty($_POST['delarc']) && !empty($_POST['id'])) {
			$isdel = $_POST['id'];
			$nowIsdel=join(',',$isdel);
			$arc->where("bid in (" . $nowIsdel . ")")->delete();
		}

		$this->assign('pages',$pa);
		$this->assign('data',$data);
		$this->display('list.html');
	}

	function changeArt()
	{
		$this->owner();
		$arc = new ArticleModel();
		
		if (!empty($_POST['cha'])) {
			$data = [
				'title' => $_POST['title'],
				'intro' => $_POST['note'],
				'content' => htmlspecialchars($_POST['test-editormd-markdown-doc']),
			];
			$arc->where('bid='.$_GET['bid'])->update($data);
		}
		$data = $arc->where('bid=' . $_GET['bid'])->select();
		$data = $data[0];

		$this->assign('data',$data);
		$this->display('add.html');
	}

	function piclist()
	{
		// var_dump($_GET,$_POST);
		$this->owner();
		$pic = new PicModel();
		$num = $pic->count('tid');
		$pagePic = new Page(5,$num);
		$picPage = $pagePic->allPage();
		$dat = $pic->limit($pagePic->limit())->select();

		if (!empty($_GET['del'])) {
			$pic->where("tid=" . $_GET['del'])->delete();
		}
		if (!empty($_POST['delpic']) && !empty($_POST['id'])) {
			$isdel = $_POST['id'];
			$nowIsdel=join(',',$isdel);
			$pic->where("tid in (" . $nowIsdel . ")")->delete();
		}

		$this->assign('pa',$picPage);
		$this->assign('dat',$dat);
		$this->display('piclist.html');
	}

	function login()
	{
		if (empty($_POST['way'])) {
			$this->display('login.html');
		} else {
			$me = new MeModel();

			if (strcasecmp($_POST['code'], $_SESSION['yzm'])) {
				echo "<script language='javascript'>alert('验证码错误，请重新输入');history.back();</script>";
				exit;
			}

			$where = 'username="' . $_POST['name'] . '" and password= "' . md5($_POST['password']) . '"';
			$my = $me->where($where)->select();
			if ($my) {
				$_SESSION['owner'] = '创始人爸爸';
				header('Location: index.php?m=admin&a=admin');
			} else {
				echo "<script language='javascript'>alert('用户名或密码错误，请重新输入');history.back();</script>";
				exit;
			}
		}
		
	}

	function cate()
	{
		$this->owner();
		$msg = new ReplyModel();
		$arc = new ArticleModel();
		if (!empty($_GET['del'])) {
			$act = $msg->where('hid=' . $_GET['del'])->field('bid')->select();
			$act = $act[0];
			$msg->where("hid=" . $_GET['del'])->delete();
			$num = $msg->where('bid=' . $act['bid'])->count('hid');
			$arr = ['reply' => $num];
			$arc->where('bid=' . $act['bid'])->update($arr);
		}
		$num = $msg->count('hid');
		$page = new Page(8,$num);
		$pages = $page->allPage();
		$data = $msg->limit($page->limit())->select();
		$this->assign('pages',$pages);
		$this->assign('data',$data);
		$this->display('cate.html');
	}

	function book()
	{
		$this->owner();
		$de = new MessageModel;
		if (!empty($_POST['way'])) {
			if (!empty($_POST['delall']) && !empty($_POST['id'])) {
				$isdel = $_POST['id'];
				$nowIsdel=join(',',$isdel);
				$de->where("id in (" . $nowIsdel . ")")->delete();
			}
		}

		if (!empty($_GET['del'])) {
			$de->where("id=" . $_GET['del'])->delete();
		}

		$model = new MessageModel();
		
		$con = $model->count('id');
		$page = new Page(5,$con);
		$msg = $model->limit($page->limit())->select();
		$pa = $page->allPage();

		$this->assign('pages',$pa);
		$this->assign('msg',$msg);
		$this->display('book.html');
	}

	function owner()
	{
		if (empty($_SESSION['owner'])) {
			exit('兄弟，你从哪来的？');
		}
	}

}