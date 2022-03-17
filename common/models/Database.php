<?php


namespace models;


class Database
{
    public static $db;

    public static function tableName() {}

    public function __construct()
    {
        $this->db        = \Yaf\Registry::get('db');
    }

    /**
     * 获取全部
     * @param $column
     * @param $where
     * @return mixed
     */
    public static function find($column = '*', $where = null): mixed
    {
        return self::getDb()->select(static::tableName(), $column, $where);
    }

    /**
     * 获取一条
     */
    public static function findOne($arg, $column = '*'): mixed
    {
        $where = is_array($arg) ? $arg : [ 'id' => $arg ];
        return self::getDb()->get(static::tableName(), $column, $where);
    }

    /**
     * 获取一个字段 一维数组
     * @param $column string
     * @param $where
     * @return array|bool
     */
    public static function column(string $column, $where = null): mixed
    {
        $list  = self::getDb()->select(static::tableName(), $column, $where);
        return $list ? array_column($list, $column) : false;
    }

    public static function insert($data): int
    {
        self::getDb()->insert(static::tableName(), $data);
        return self::getDb()->id();
    }

    /**
     * 插入多行
     * @param $data
     * @return int
     */
    public static function insertMany($data): int
    {
        $data  = self::getDb()->insert(static::tableName(), $data);
        return $data->rowCount();
    }

    /**
     * 修改数据
     * @param $data
     * @param $where
     * @return int
     */
    public static function update($data, $where): int
    {
        $data = self::getDb()->update(static::tableName(), $data, $where);
        return $data->rowCount();
    }

    /**
     * 删除数据
     * @param $where
     * @return int
     */
    public static function delete($where): int
    {
        $data = self::getDb()->delete(static::tableName(), $where);
        return $data->rowCount();
    }

    /**
     * 查询条目数
     * @param $where
     */
    public static function count($where = null)
    {
        return self::getDb()->count(static::tableName(), $where);
    }

    /**
     * 数据是否存在
     * @param $where
     * @return bool
     */
    public static function exis($where): bool
    {
        return self::getDb()->has(static::tableName(), $where);
    }

    public static function getDb()
    {
        if (self::$db === null)
            self::$db        = \Yaf\Registry::get('db');

        return self::$db;
    }
}