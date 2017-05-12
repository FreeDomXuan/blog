<?php
namespace model;
use vendor\Model;
class ReplyModel extends Model
{
	public function __construct()
	{
		parent::__construct($GLOBALS['config']);
	}
}