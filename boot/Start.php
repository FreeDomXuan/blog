<?php

class Start
{
	//用来存放自动加载对象的
	static public $auto;
	
	static function init()
	{
		self::$auto = new Psr4AutoLoad();
	}
	
	static function router()
	{
		$m = empty($_GET['m']) ? 'index' : $_GET['m'];
		$a = empty($_GET['a']) ? 'index' : $_GET['a'];
		$_GET['m'] = $m;
		$_GET['a'] = $a;
		//拼接控制器的名字
		$className = 'controller\\' . ucfirst($m) . 'Controller';
		$obj = new $className();
		call_user_func([$obj, $a]);
	}
}

Start::init();