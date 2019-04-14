<?php
/**
 * Created by PhpStorm.
 * User: Tioncico
 * Date: 2019/4/14 0014
 * Time: 12:07
 */
include "../vendor/autoload.php";
defined('EASYSWOOLE_ROOT') or define('EASYSWOOLE_ROOT', dirname(__FILE__, 2));
require_once EASYSWOOLE_ROOT . '/EasySwooleEvent.php';
\EasySwoole\EasySwoole\Core::getInstance()->initialize();

go(function (){
    $db = \App\Utility\Pool\MysqlPool::defer();
    $mysqlTable = new \AutoWriteCode\MysqlTable($db, \EasySwoole\EasySwoole\Config::getInstance()->getConf('MYSQL.database'));
    $tableColumns = $mysqlTable->getColumnList('test');
    $tableComment = $mysqlTable->getComment('test');
    $modelBuilder = new \AutoWriteCode\ModelBuilder(dirname(__FILE__, 2) . '/Application/Model', 'App\Model', \App\Model\BaseModel::class);
    $modelBuilder->generateModel('test', $tableComment, $tableColumns);
    exit;

});


/**
 * 生成bean
 */
//go(function () {
//    $db = \App\Utility\Pool\MysqlPool::defer();
//    $mysqlTable = new \AutoWriteCode\MysqlTable($db, \EasySwoole\EasySwoole\Config::getInstance()->getConf('MYSQL.database'));
//    $tableColumns = $mysqlTable->getColumnList('test');
//    $tableComment = $mysqlTable->getComment('test');
//    $beanBuilder = new \AutoWriteCode\BeanBuilder(dirname(__FILE__, 2) . '/Application/Model', 'App\Model', '');
//    $beanBuilder->generateBean('test', $tableComment, $tableColumns);
//    exit;
//});