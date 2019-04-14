<?php

namespace App\Model;

/**
 * 注释
 * Class TestBean
 * Create With Automatic Generator
 * @property int id |
 * @property string name |
 * @property int testId |
 * @property int addTime |
 * @property string note |
 * @property string othe |
 */
class TestBean extends \EasySwoole\Spl\SplBean
{
	protected $id;

	protected $name;

	protected $testId;

	protected $addTime;

	protected $note;

	protected $othe;


	public function setId($id){$this->id=$id;}


	public function getId(){ return $this->id;}


	public function setName($name){$this->name=$name;}


	public function getName(){ return $this->name;}


	public function setTestId($testId){$this->testId=$testId;}


	public function getTestId(){ return $this->testId;}


	public function setAddTime($addTime){$this->addTime=$addTime;}


	public function getAddTime(){ return $this->addTime;}


	public function setNote($note){$this->note=$note;}


	public function getNote(){ return $this->note;}


	public function setOthe($othe){$this->othe=$othe;}


	public function getOthe(){ return $this->othe;}
}

