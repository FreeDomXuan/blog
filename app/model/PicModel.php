<?php
namespace model;
use vendor\Model;
class PicModel extends Model
{
	public function __construct()
	{
		parent::__construct($GLOBALS['config']);
	}
}