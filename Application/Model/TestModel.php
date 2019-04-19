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
	 * @keyword name
	 * @param  int  page  1
	 * @param  string  keyword
	 * @param  int  pageSize  10
	 * @return array[total,list]
	 */
	public function getAll(int $page = 1, string $keyword = null, int $pageSize = 10): array
	{
		if (!empty($keyword)) {
		    $this->getDbConnection()->where('name', '%' . $keyword . '%', 'like');
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
	 * @param  TestBean $bean
	 * @return TestBean
	 */
	public function getOne(TestBean $bean): ?TestBean
	{
		$info = $this->getDbConnection()->where($this->primaryKey, $bean->getId())->getOne($this->table);
		if (empty($info)) {
		    return null;
		}
		return new TestBean($info);
	}


	/**
	 * 默认根据bean数据进行插入数据
	 * @add
	 * @param  TestBean $bean
	 * @return bool
	 */
	public function add(TestBean $bean): bool
	{
		return $this->getDbConnection()->insert($this->table, $bean->toArray(null, $bean::FILTER_NOT_NULL));
	}


	/**
	 * 默认根据主键(id)进行删除
	 * @delete
	 * @param  TestBean $bean
	 * @return bool
	 */
	public function delete(TestBean $bean): bool
	{
		return  $this->getDbConnection()->where($this->primaryKey, $bean->getId())->delete($this->table);
	}


	/**
	 * 默认根据主键(id)进行更新
	 * @delete
	 * @param  TestBean $bean
	 * @param  array $data
	 * @return bool
	 */
	public function update(TestBean $bean, array $data): bool
	{
		if (empty($data)){
		    return false;
		}
		return $this->getDbConnection()->where($this->primaryKey, $bean->getId())->update($this->table, $data);
	}
}

