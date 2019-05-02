<?php
/**
 * Created by PhpStorm.
 * User: tioncico
 * Date: 19-5-2
 * Time: 上午10:38
 */

namespace AutoWriteCode;

use AutoWriteCode\Config\ControllerConfig;
use EasySwoole\Http\Message\Status;
use EasySwoole\Utility\Str;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

/**
 * easyswoole 控制器快速构建器
 * Class ControllerBuilder
 * @package AutoWriteCode
 */
class ControllerBuilder
{
    /**
     * @var $config BeanConfig;
     */
    protected $config;

    /**
     * BeanBuilder constructor.
     * @param        $config
     * @throws \Exception
     */
    public function __construct(ControllerConfig $config)
    {
        $this->config = $config;
        $this->createBaseDirectory($config->getBaseDirectory());
    }

    /**
     * createBaseDirectory
     * @param $baseDirectory
     * @throws \Exception
     * @author Tioncico
     * Time: 19:49
     */
    protected function createBaseDirectory($baseDirectory)
    {
        if (!is_dir((string)$baseDirectory)) {
            if (!@mkdir($baseDirectory, 0755)) throw new \Exception("Failed to create directory {$baseDirectory}");
            @chmod($baseDirectory, 0755);  // if umask
            if (!is_writable($baseDirectory)) {
                throw new \Exception("The directory {$baseDirectory} cannot be written. Please set the permissions manually");
            }
        }
    }

    /**
     * generateBean
     * @return bool|int
     * @author Tioncico
     * Time: 19:49
     */
    public function generateController()
    {
        $realTableName = $this->setRealTableName();

        $phpNamespace = new PhpNamespace($this->config->getBaseNamespace());
        $phpNamespace->addUse($this->config->getMysqlPoolClass());
        $phpNamespace->addUse($this->config->getModelClass());
        $phpNamespace->addUse($this->config->getBeanClass());
        $phpNamespace->addUse(Status::class);
        $phpClass = $phpNamespace->addClass($realTableName);
        $phpClass->addExtend($this->config->getExtendClass());
        $phpClass->addComment("{$this->config->getTableComment()}");
        $phpClass->addComment("Class {$realTableName}");
        $phpClass->addComment('Create With Automatic Generator');
        $this->addAddDataMethod($phpClass);


        return $this->createPHPDocument($this->config->getBaseDirectory() . '/' . $realTableName, $phpNamespace, $this->config->getTableColumns());
    }

    function addAddDataMethod(ClassType $phpClass){
        $addData = [];
        $method = $phpClass->addMethod('add');
        $apiUrl = str_replace(['App\\HttpController','\\'],['','/'],$this->config->getBaseNamespace());
        //配置基础注释
        $method->addComment("@api {get|post} {$apiUrl}/{$this->setRealTableName()}/add");
        $method->addComment("@apiName add");
        $method->addComment("@apiGroup {$apiUrl}/{$this->setRealTableName()}");
        $method->addComment("@apiPermission {$this->config->getAuthName()}");
        $method->addComment("@apiDescription add新增数据");
        $this->config->getAuthSessionName()&&($method->addComment("* @apiParam {String}  {$this->config->getAuthSessionName()} 权限验证token"));
        $mysqlPoolNameArr = (explode('\\',$this->config->getMysqlPoolClass()));
        $mysqlPoolName = end($mysqlPoolNameArr);
        $modelNameArr = (explode('\\',$this->config->getModelClass()));
        $modelName = end($modelNameArr);
        $beanNameArr = (explode('\\',$this->config->getBeanClass()));
        $beanName = end($beanNameArr);
        $methodBody = <<<Body
\$db = {$mysqlPoolName}::defer();
\$param = \$this->request()->getRequestParam();
\$model = new {$modelName}(\$db);
\$bean = new {$beanName}();

Body;
        foreach ($this->config->getTableColumns() as $column){
            if ($column['Key']!='PRI'){
                $addData[]=$column['Field'];
                $columnType = $this->convertDbTypeToDocType($column['Type']);
                $setMethodName = "set" . Str::studly($column['Field']);
                if ($column['Null']=='NO'){
                    $method->addComment("@apiParam {$columnType} {$column['Field']} {$column['Comment']}");
                    $methodBody.="\$bean->$setMethodName(\$param['{$column['Field']}']);\n";
                }else{
                    $method->addComment("@apiParam {$columnType} [{$column['Field']}] {$column['Comment']}");
                    $methodBody.="\$bean->$setMethodName(\$param['{$column['Field']}']);\n";
                }
            }
        }
        $methodBody.=<<<Body
\$rs = \$model->add(\$bean);
if (\$rs) {
    \$this->writeJson(Status::CODE_OK, \$rs, "success");
} else {
    \$this->writeJson(Status::CODE_BAD_REQUEST, [], \$db->getLastError());
}
Body;
        $method->setBody($methodBody);
        $method->addComment("@apiSuccess {Number} code");
        $method->addComment("@apiSuccess {Object[]} data");
        $method->addComment("@apiSuccess {String} msg");
        $method->addComment("@apiSuccessExample {json} Success-Response:");
        $method->addComment("HTTP/1.1 200 OK");
        $method->addComment("@author: autoWriteCode < 1067197739@qq.com >");
    }




    /**
     * 处理表真实名称
     * setRealTableName
     * @return bool|mixed|string
     * @author tioncico
     * Time: 下午11:55
     */
    function setRealTableName()
    {
        if ($this->config->getRealTableName()){
            return $this->config->getRealTableName();
        }
        //先去除前缀
        $tableName = substr($this->config->getTableName(), strlen($this->config->getTablePre()));
        //去除后缀
        $tableName = str_replace($this->config->getIgnoreString(),'',$tableName);
        //下划线转驼峰,并且首字母大写
        $tableName = ucfirst(Str::camel($tableName));
        $this->config->setRealTableName($tableName);
        return $tableName;
    }

    function getColumnDeaflutValue($column){

    }

    /**
     * convertDbTypeToDocType
     * @param $fieldType
     * @return string
     * @author Tioncico
     * Time: 19:49
     */
    protected function convertDbTypeToDocType($fieldType)
    {
        $newFieldType = strtolower(strstr($fieldType, '(', true));
        if ($newFieldType == '') $newFieldType = strtolower($fieldType);
        if (in_array($newFieldType, ['tinyint', 'smallint', 'mediumint', 'int', 'bigint'])) {
            $newFieldType = 'int';
        } elseif (in_array($newFieldType, ['float', 'double', 'real', 'decimal', 'numeric'])) {
            $newFieldType = 'float';
        } elseif (in_array($newFieldType, ['char', 'varchar', 'text'])) {
            $newFieldType = 'string';
        } else {
            $newFieldType = 'mixed';
        }
        return $newFieldType;
    }

    /**
     * createPHPDocument
     * @param $fileName
     * @param $fileContent
     * @param $tableColumns
     * @return bool|int
     * @author Tioncico
     * Time: 19:49
     */
    protected function createPHPDocument($fileName, $fileContent, $tableColumns)
    {
//        if (file_exists($fileName . '.php')) {
//            echo "(Bean)当前路径已经存在文件,是否覆盖?(y/n)\n";
//            if (trim(fgets(STDIN)) == 'n') {
//                echo "已结束运行";
//                return false;
//            }
//        }
        $content = "<?php\n\n{$fileContent}\n";
        $result = file_put_contents($fileName . '.php', $content);

        return $result == false ? $result : $fileName . '.php';
    }


}