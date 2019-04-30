<?php
/**
 * Created by PhpStorm.
 * User: tioncico
 * Date: 19-5-1
 * Time: 上午12:04
 */

namespace AutoWriteCode;


use App\Utility\Pool\MysqlPoolObject;

class init
{
    protected $appPath;

    public function __construct($appPath = 'Application')
    {
        defined('EASYSWOOLE_ROOT') or define('EASYSWOOLE_ROOT', dirname(__FILE__, 2));
        require_once EASYSWOOLE_ROOT . '/EasySwooleEvent.php';
        \EasySwoole\EasySwoole\Core::getInstance()->initialize();
        $this->appPath = $appPath;
    }

    function initBaseModel($poolObjectName=null)
    {
        $poolObjectName = $poolObjectName??MysqlPoolObject::class;




    }
}