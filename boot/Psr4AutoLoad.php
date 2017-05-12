<?php

class Psr4AutoLoad
{
	//用来存放映射关系的数组
	protected $maps = [];
	//创建对象就注册自己的函数为自动加载函数
	function __construct()
	{
		spl_autoload_register([$this, 'autoload']);
	}
	function autoload($className)
	{
		//带有命名空间的类名  controller\IndexController
		$pos = strrpos($className, '\\');
		//将命名空间给拿出来
		$namespace = substr($className, 0, $pos);
		//将真正的类名拿出来
		$realClassName = substr($className, $pos + 1);
		
		//去对应的数组中查找有没有映射关系
		$namespace = $this->mapLoad($namespace);
		//拼接真正的文件路径
		$filePath = $namespace . $realClassName . '.php';
		//将文件包含进来
		if (file_exists($filePath)) {
			include $filePath;
		} else {
			die('该文件不存在');
		}
	}
	
	//根据命名空间去数组中查找对应的目录结构
	protected function mapLoad($namespace)
	{
		if (array_key_exists($namespace, $this->maps)) {
			$namespace = $this->maps[$namespace];
		}
		//将字符串中所有的反斜线换成正斜线
		$namespace = str_replace('\\', '/', $namespace);
		return rtrim($namespace, '/') . '/';
	}
	
	//添加映射关系的方法
	function addMaps($namespace, $path)
	{
		if (array_key_exists($namespace, $this->maps)) {
			die('该映射关系已经存在，请勿添加');
		}
		$this->maps[$namespace] = $path;
	}
}










