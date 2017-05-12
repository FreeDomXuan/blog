<?php

namespace controller;
use \model\PicModel;
use \vendor\Page;

class Portfolio3Controller extends Controller
{
	function portfolio3()
	{
		$title = '动态列表 - 问君随风';
		$pic = new PicModel();
		$con = $pic->where('type=0')->count('tid');
		$page = new Page(9,$con);
		$pa = $page->allPage();
		$pics = $pic->where('type=0')->order('picorder desc')->limit($page->limit())->select();
		$this->assign('pics',$pics);
		$this->assign('pages',$pa);
		$this->assign('title', $title);

		$this->display();
	}
}