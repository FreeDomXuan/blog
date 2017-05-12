<?php
namespace model;
use vendor\Model;
class LinkModel extends Model
{
	public function __construct()
	{
		parent::__construct($GLOBALS['config']);
	}

	public function getLink()
	{
		$link = $this->order('displayorder')->field('linkname,url,description,logo')->select();
		return $link;
	}
}