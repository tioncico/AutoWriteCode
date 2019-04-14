<?php
/**
 * Created by PhpStorm.
 * User: Tioncico
 * Date: 2019/4/14 0014
 * Time: 22:28
 */

class TableAutomatic
{
    protected $tableName;
    protected $tablePre;

    public function __construct($tableName,$tablePre)
    {
        $this->tableName = $tableName;
        $this->tablePre = $tablePre;
    }

}