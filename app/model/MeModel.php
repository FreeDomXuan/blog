<?php
namespace model;
use vendor\Model;
class MeModel extends Model
{
	public function __construct()
	{
		parent::__construct($GLOBALS['config']);
	}
}