<?php

namespace controller;
use \model\ArticleModel;
use \model\ReplyModel;

class PostController extends Controller
{
	function Post()
	{
		if (empty($_GET['id'])) {
			die('禁止非法操作');
		} else {
			$bid = $_GET['id'];
		}
		$where = 'bid=' . $bid;
		$wen = new ArticleModel();
		$wen->addHits($bid);
		
		$data = $wen->where($where)->select();	
		$data = $data[0];
		if ($data['hits'] > 500) {
			$tui = ['recommend' => 1];
			$wen->where($where)->update($tui);
		}

		if (!empty($_POST['way'])) {
			$this->addReply();
		}
		
		$reply = new ReplyModel();
		$where = 'pid=0 and bid=' . $bid;
		$rep1 = $reply->where($where)->select();

		$where = 'pid<>0 and bid=' . $bid;
		$rep2 = $reply->where($where)->select();

		$this->assign('rep2', $rep2);
		$this->assign('rep1', $rep1);
		$this->assign('title', $data['title']);
		$this->assign('data', $data);
		$this->display();
	}

	function addReply()
	{
		if($_SERVER['REMOTE_ADDR'] == '::1'){
			$ip = ip2long('127.0.0.1');
		}else{
			$ip = ip2long($_SERVER['REMOTE_ADDR']);
		}

		$pid = empty($_GET['pid']) ? 0 : $_GET['pid'];

		$data = [
			'author' => $_POST['author'],
			'bid' => $_GET['id'],
			'pid' => $pid,
			'content' => $_POST['content'],
			'addtime' => time(),
			'addip' => $ip,
			'email' => $_POST['email'],
			'pic' => 'public/images/asd' . mt_rand(1,9) . '.png',
		];

		$reply = new ReplyModel();
		$reply->insert($data);

		$con = $reply->where('bid='.$_GET['id'])->count('hid');

		$wen = new ArticleModel();

		$hui = ['reply' => $con];

		$wen->where('bid='.$_GET['id'])->update($hui);
	}
}