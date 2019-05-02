# AutoWriteCode
EasySwoole自动写代码 Bean Model Controller自动生成工具
````php
<?php
include "../vendor/autoload.php";
defined('EASYSWOOLE_ROOT') or define('EASYSWOOLE_ROOT', dirname(__FILE__, 2));
require_once EASYSWOOLE_ROOT . '/EasySwooleEvent.php';
\EasySwoole\EasySwoole\Core::getInstance()->initialize();
go(function ()  {
    $automatic = new \AutoWriteCode\TableAutomatic('test', '');
    $automatic->action();
});
````
只需要AutoWriteCode目录下的东西即可

## 生成bean
````php
<?php
include "../vendor/autoload.php";
defined('EASYSWOOLE_ROOT') or define('EASYSWOOLE_ROOT', dirname(__FILE__, 2));
require_once EASYSWOOLE_ROOT . '/EasySwooleEvent.php';
\EasySwoole\EasySwoole\Core::getInstance()->initialize();
go(function ()  {
    $automatic = new \AutoWriteCode\TableAutomatic('user_list', '');
    $beanConfig = new \AutoWriteCode\Config\BeanConfig();
    $beanConfig->setBaseDirectory(EASYSWOOLE_ROOT . '/' . $automatic::APP_PATH . '/Model');
    $beanConfig->setBaseNamespace("APP\\Model");
    $beanConfig->setTablePre('');
    $beanConfig->setTableName('user_list');
    $beanConfig->setTableComment($automatic->tableComment);
    $beanConfig->setTableColumns($automatic->tableColumns);
    $beanBuilder = new \AutoWriteCode\BeanBuilder($beanConfig);
    $result = $beanBuilder->generateBean();
    var_dump($result);
    exit();
});
````
## 生成Model
````php
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

    $modelConfig = new \AutoWriteCode\Config\ModelConfig();
    $modelConfig->setBaseDirectory(EASYSWOOLE_ROOT . '/' . $automatic::APP_PATH . '/Model');
    $modelConfig->setBaseNamespace("App\\Model");
    $modelConfig->setTablePre("");
    $modelConfig->setExtendClass(\App\Model\BaseModel::class);
    $modelConfig->setTableName("user_list");
    $modelConfig->setTableComment($automatic->tableComment);
    $modelConfig->setTableColumns($automatic->tableColumns);
    $modelBuilder = new \AutoWriteCode\ModelBuilder($modelConfig);
    $result = $modelBuilder->generateModel();
    var_dump($result);
    exit();
});
````

## 生成controller
````php
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
    $controllerBuilder = new \AutoWriteCode\ControllerBuilder($controllerConfig);
    $controllerBuilder->generateController();
    exit();
});
````