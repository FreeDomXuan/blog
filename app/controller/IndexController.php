<?php

namespace controller;
use \model\LinkModel;
use \model\ArticleModel;

class IndexController extends Controller
{
	function index()
	{
		//获取友链信息
		$res = new LinkModel();
		$link = $res->getLink();
		//获取推荐文章信息
		$wen = new ArticleModel();
		$pops = $wen->getPopBlog();	

		$title = '问君随风';
		$this->assign('pops',$pops);
		$this->assign('link', $link);
		$this->assign('title', $title);
		$this->display();
	}
}