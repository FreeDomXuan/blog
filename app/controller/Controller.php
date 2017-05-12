<?php

namespace controller;
use vendor\Tpl;
class Controller extends Tpl
{
	function __construct()
	{
		//获取外部变量$config
		$config = $GLOBALS['config'];
		parent::__construct($config['TPL_VIEW'], $config['TPL_CACHE']);
	}
	
	function display($viewName = null, $isInclude = true, $uri = null)
	{
		if (empty($viewName)) {
			$viewName = $_GET['m'] . '/' . $_GET['a'] . '.html';
		} else {
			$viewName = $_GET['m'] . '/' . $viewName;
		}
		parent::display($viewName, $isInclude, $uri);
	}
}





