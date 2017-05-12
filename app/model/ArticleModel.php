<?php
namespace model;
use vendor\Model;
class ArticleModel extends Model
{
	public function __construct()
	{
		parent::__construct($GLOBALS['config']);
	}

	public function getPopBlog()
	{
		$pop = $this->where('recommend=1')->order('reply desc,hits desc')->limit('3')->select();
		return $pop;
	}

	public function getBlogDate()
	{
		//将文章发表时间按月份归档
		$sql = "SELECT FROM_UNIXTIME(posttime,'%M-%Y') months FROM blog_article GROUP BY months";
		$re = mysqli_query($this->link, $sql);
		$dateMY = mysqli_fetch_all($re, MYSQLI_ASSOC);
		return $dateMY;
	}

	public function getDateBlog($date,$limit)
	{
		$sql = "SELECT * FROM blog_article WHERE FROM_UNIXTIME(posttime,'%M-%Y')='" . $date . "' limit " . $limit ;
		$re = mysqli_query($this->link, $sql);
		$blogMY = mysqli_fetch_all($re, MYSQLI_ASSOC);
		return $blogMY;
	}

	public function addHits($bid)
	{
		$sql = 'update blog_article set hits = hits+1 where bid=' . $bid;
		$re = mysqli_query($this->link, $sql);
	}
}
