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