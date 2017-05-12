<?php

namespace controller;
use \model\PicModel;

class GalleryController extends Controller
{
	function gallery()
	{
		$title = '相册 - 问君随风';
		$pic = new PicModel();

		$pics = $pic->order('picorder desc')->select();
		$this->assign('pics',$pics);
		$this->assign('title', $title);

		$this->display();
	}
}