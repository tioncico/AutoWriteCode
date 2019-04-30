<?php
/**
 * Created by PhpStorm.
 * User: tioncico
 * Date: 19-4-30
 * Time: 下午11:22
 */

namespace AutoWriteCode\Config;


use EasySwoole\Spl\SplBean;

class ModelConfig extends SplBean
{
    protected $extendClass;//继承的基类
    protected $baseDirectory;//生成的目录
    protected $baseNamespace;//生成的命名空间
    protected $tablePre = '';//数据表前缀
    protected $ignoreString = [
        'list',
        'log'
    ];//文件名生成时,忽略的字符串(list,log等)
    protected $primaryKey;

    /**
     * @return mixed
     */
    public function getExtendClass()
    {
        return $this->extendClass;
    }

    /**
     * @param mixed $extendClass
     */
    public function setExtendClass($extendClass)
    {
        $this->extendClass = $extendClass;
    }

    /**
     * @return mixed
     */
    public function getBaseDirectory()
    {
        return $this->baseDirectory;
    }

    /**
     * @param mixed $baseDirectory
     */
    public function setBaseDirectory($baseDirectory)
    {
        $this->baseDirectory = $baseDirectory;
    }

    /**
     * @return mixed
     */
    public function getBaseNamespace()
    {
        return $this->baseNamespace;
    }

    /**
     * @param mixed $baseNamespace
     */
    public function setBaseNamespace($baseNamespace)
    {
        $this->baseNamespace = $baseNamespace;
    }

    /**
     * @return string
     */
    public function getTablePre()
    {
        return $this->tablePre;
    }

    /**
     * @param string $tablePre
     */
    public function setTablePre($tablePre)
    {
        $this->tablePre = $tablePre;
    }

    /**
     * @return array
     */
    public function getIgnoreString()
    {
        return $this->ignoreString;
    }

    /**
     * @param array $ignoreString
     */
    public function setIgnoreString($ignoreString)
    {
        $this->ignoreString = $ignoreString;
    }

    /**
     * @return mixed
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * @param mixed $primaryKey
     */
    public function setPrimaryKey($primaryKey)
    {
        $this->primaryKey = $primaryKey;
    }//模型的主键
}