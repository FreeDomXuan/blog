<?php

namespace controller;
use \model\PicModel;
use \vendor\Page;

class Portfolio2Controller extends Controller
{
	function portfolio2()
	{
		$title = '旅行留念 - 问君随风';
		$pic = new PicModel();
		$con = $pic->where('type=1')->count('tid');
		$page = new Page(6,$con);
		$pa = $page->allPage();
		$pics = $pic->where('type=1')->order('picorder desc')->limit($page->limit())->select();
		$this->assign('pics',$pics);
		$this->assign('pages',$pa);
		$this->assign('title', $title);
		
		$this->display();
	}
}