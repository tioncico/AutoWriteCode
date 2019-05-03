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

go(function ()  {
    $automatic = new \AutoWriteCode\TableAutomatic('user_list', '');
    $controllerConfig = new \AutoWriteCode\Config\ControllerConfig();
    $controllerConfig->setBaseDirectory( EASYSWOOLE_ROOT . '/' . $automatic::APP_PATH . '/Api');
    $controllerConfig->setBaseNamespace($automatic->controllerNamespace);
    $controllerConfig->setTablePre($automatic->tablePre);
    $controllerConfig->setTableName($automatic->tableName);
    $controllerConfig->setTableComment($automatic->tableComment);
    $controllerConfig->setTableColumns($automatic->tableColumns);
    $controllerConfig->setExtendClass($extendClass ?? \App\HttpController\Api\ApiBase::class);
    $controllerConfig->setModelClass(\App\Model\UserModel::class);
    $controllerConfig->setBeanClass(\App\Model\UserBean::class);
    $controllerConfig->setMysqlPoolClass(\App\Utility\Pool\MysqlPool::class);
    $controllerConfig->setAuthName('admin');
    $controllerConfig->setAuthSessionName('adminSession');
    $controllerBuilder = new \AutoWriteCode\ControllerBuilder($controllerConfig);
    $controllerBuilder->generateController();
    exit();
});