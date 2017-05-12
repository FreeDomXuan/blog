<?php
namespace model;
use vendor\Model;
class MessageModel extends Model
{
	public function __construct()
	{
		parent::__construct($GLOBALS['config']);
	}
}