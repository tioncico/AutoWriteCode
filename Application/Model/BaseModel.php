<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/10/26
 * Time: 4:07 PM
 */

namespace App\Model;


use App\Utility\Pool\MysqlPoolObject;

class BaseModel
{
    private $db;
    function __construct(MysqlPoolObject $dbObject)
    {
        $this->db = $dbObject;
    }

    function getDbConnection():MysqlPoolObject
    {
        return $this->db;
    }
}