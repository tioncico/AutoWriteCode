<?php
/**
 * Created by PhpStorm.
 * User: Tioncico
 * Date: 2019/4/14 0014
 * Time: 22:28
 */

namespace AutoWriteCode;
class TableAutomatic
{
    const APP_PATH = 'Application';
    protected $tableName;
    protected $tablePre;
    protected $baseDir;
    protected $namespace;

    public function __construct($tableName, $tablePre, $baseDir = null, $namespace = null)
    {
        $this->tableName = $tableName;
        $this->tablePre = $tablePre;
        $this->baseDir = $baseDir ?? EASYSWOOLE_ROOT . '/' . self::APP_PATH . '/Model';
        $this->namespace = $namespace ?? 'App\Model';
    }

    function action($generateBean = true, $generateModel = true, $generateController = true)
    {
        go(function () use ($generateBean, $generateModel, $generateController) {
            $db = \App\Utility\Pool\MysqlPool::defer();
            $mysqlTable = new MysqlTable($db, \EasySwoole\EasySwoole\Config::getInstance()->getConf('MYSQL.database'));
            $tableName = 'test';
            $tableColumns = $mysqlTable->getColumnList($tableName);
            $tableComment = $mysqlTable->getComment($tableName);
            if ($generateBean) {
                $result = $this->generateBean($tableColumns, $tableComment);
                if ($result) {
                    echo "生成[{$result}]成功\n";
                } else {
                    echo "生成bean失败";
                }
            }
            if ($generateModel) {
                $result = $this->generateModel($tableColumns, $tableComment);
                if ($result) {
                    echo "生成[{$result}]成功\n";
                } else {
                    echo "生成Model失败";
                }
            }
            if ($generateController) {
//                $result = $this->generateBean($tableColumns,$tableComment);
//                if ($result){
//                    echo "生成[{$result}]成功\n";
//                }else{
//                    echo "生成bean失败";
//                }
            }

            exit;
        });
    }

    function generateModel($tableColumns, $tableComment)
    {
        $modelBuilder = new ModelBuilder($this->baseDir, $this->namespace, \App\Model\BaseModel::class);
        return $modelBuilder->generateModel($this->tableName, $tableComment, $tableColumns);
    }

    function generateBean($tableColumns, $tableComment)
    {
        $beanBuilder = new BeanBuilder($this->baseDir, $this->namespace);
        return $beanBuilder->generateBean($this->tableName, $tableComment, $tableColumns);
    }

}