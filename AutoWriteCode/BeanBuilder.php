<?php
/**
 * Created by PhpStorm.
 * User: eValor
 * Date: 2018/11/10
 * Time: 上午1:52
 */

namespace AutoWriteCode;

use EasySwoole\Utility\Str;
use Nette\PhpGenerator\PhpNamespace;

/**
 * easyswoole Bean快速构建器
 * Class BeanBuilder
 * @package AutoWriteCode
 */
class BeanBuilder
{
    protected $basePath;
    protected $nameType = 1;
    protected $baseNamespace;
    protected $tablePre = '';

    /**
     * BeanBuilder constructor.
     * @param        $baseDirectory
     * @param        $baseNamespace
     * @param string $tablePre
     * @throws \Exception
     */
    public function __construct($baseDirectory, $baseNamespace, $tablePre = '')
    {
        $this->basePath = $baseDirectory;
        $this->createBaseDirectory($baseDirectory);
        $this->baseNamespace = $baseNamespace;
        $this->tablePre = $tablePre;
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
     * @param $tableName
     * @param $tableComment
     * @param $tableColumns
     * @return bool|int
     * @author Tioncico
     * Time: 19:49
     */
    public function generateBean($tableName, $tableComment, $tableColumns)
    {
        $realTableName = ucfirst(Str::camel(substr($tableName, strlen($this->tablePre)))) . 'Bean';

        $phpNamespace = new PhpNamespace($this->baseNamespace);
        $phpClass = $phpNamespace->addClass($realTableName);
        $phpClass->addExtend("EasySwoole\Spl\\SplBean");
        $phpClass->addComment("{$tableComment}");
        $phpClass->addComment("Class {$realTableName}");
        $phpClass->addComment('Create With Automatic Generator');
        foreach ($tableColumns as $column) {
            $name = $column['Field'];
            $comment = $column['Comment'];
            $columnType = $this->convertDbTypeToDocType($column['Type']);
            $phpClass->addComment("@property {$columnType} {$name} | {$comment}");
            $phpClass->addProperty($column['Field']);
            $phpClass->addMethod("set" . Str::studly($column['Field']));
            $phpClass->addMethod("get" . Str::studly($column['Field']));
        }
        return $this->createPHPDocument($this->basePath . '/' . $realTableName, $phpNamespace, $tableColumns);
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
        var_dump($fileName.'.php');
        if (file_exists($fileName.'.php')){
            echo "当前路径已经存在文件,是否覆盖?(y/n)\n";
            if (trim(fgets(STDIN))=='n'){
                echo "已结束运行";
                return false;
            }
        }
        $fileContent = $this->publicPropertyToProtected($fileContent, $tableColumns);
        $fileContent = $this->addGetMethodContent($fileContent, $tableColumns);
        $content = "<?php\n\n{$fileContent}\n";
        return file_put_contents($fileName . '.php', $content);
    }

    /**
     * publicPropertyToProtected
     * @param $fileContent
     * @param $tableColumns
     * @return mixed
     * @author Tioncico
     * Time: 19:50
     */
    protected function publicPropertyToProtected($fileContent, $tableColumns)
    {
        foreach ($tableColumns as $column) {
            $fileContent = str_replace("public $" . $column['Field'] . ";", "protected $" . $column['Field'] . ";", $fileContent);
        }
        return $fileContent;
    }

    /**
     * addGetMethodContent
     * @param $fileContent
     * @param $tableColumns
     * @return string|string[]|null
     * @author Tioncico
     * Time: 19:50
     */
    protected function addGetMethodContent($fileContent, $tableColumns)
    {
        foreach ($tableColumns as $column) {
            $pattern = '/(public\\s+function\\s+set' . Str::studly($column['Field']) . ')\(\)\\s+({)(\\s*)(})/';
            $fileContent = preg_replace($pattern, '$1($' . Str::camel($column['Field']) . ')$2$this->' . $column['Field'] . '=$' . Str::camel($column['Field']) . ';$4', $fileContent);
            $pattern = '/(public\\s+function\\s+get' . Str::studly($column['Field']) . ')\(\)\\s+({)(\\s*)(})/';
            $fileContent = preg_replace($pattern, '$1()$2 return $this->' . $column['Field'] . ';$4', $fileContent);
        }
        return $fileContent;
    }
}