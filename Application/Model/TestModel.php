<?php

namespace App\Model;

/**
 * 注释
 * Class TestModel
 * Create With Automatic Generator
 */
class TestModel extends BaseModel
{
	protected $table = 'test';

	protected $primaryKey = 'id';


	/**
	 * @getAll
	 * @keyword 1
	 * @param  int  page  1
	 * @param  string  keyword
	 * @param  int  pageSize  10
	 * @return array[total,list]
	 */
	public function getAll(int $page = 1, string $keyword = null, int $pageSize = 10): array
	{
		if (!empty($keyword)) {
		    $this->getDbConnection()->where('customerCaseName', '%' . $keyword . '%', 'like');
		}

		$list = $this->getDbConnection()
		    ->withTotalCount()
		    ->orderBy($this->primaryKey, 'DESC')
		    ->get($this->table, [$pageSize * ($page  - 1), $pageSize]);
		$total = $this->getDbConnection()->getTotalCount();
		return ['total' => $total, 'list' => $list];
	}


	/**
	 * 默认根据主键(id)进行搜索
	 * @getOne
	 * @param  App\Model\TestBean
	 */
	public function getOne(TestBean $bean): ?TestBean
	{
	}
}

