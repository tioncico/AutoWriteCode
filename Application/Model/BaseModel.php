<?php

namespace App\Model;

/**
 * BaseModel
 * Class BaseModel
 * Create With Automatic Generator
 */
class BaseModel
{
	protected $db;


	public function __construct(\App\Utility\Pool\MysqlPoolObject $dbObject)
	{
		$this->db = $dbObject;
	}


	public function getDbConnection(): \App\Utility\Pool\MysqlPoolObject
	{
		return $this->db;
	}
}

