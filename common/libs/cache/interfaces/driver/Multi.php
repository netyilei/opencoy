<?php

namespace libs\cache\interfaces\driver;

/**
 * Multi
 */
interface Multi {

    /**
     * 批量设置键值
     * @param array $sets   键值数组
     * @return boolean      是否成功
     */
    public function mSet($sets);

    /**
     * 批量设置键值(当键名不存在时);<br>
     * 只有当键值全部设置成功时,才返回true,否则返回false并尝试回滚
     * @param array $sets   键值数组
     * @return boolean      是否成功
     */
    public function mSetNX($sets);

    /**
     * 批量获取键值
     * @param array $keys   键名数组
     * @return array|false  键值数组,失败返回false
     */
    public function mGet($keys);

    /**
     * 批量判断键值是否存在
     * @param array $keys   键名数组
     * @return array  返回存在的keys
     */
    public function mHas($keys);

    /**
     * 批量删除键值
     * @param array $keys   键名数组
     * @return boolean  是否成功
     */
    public function mDel($keys);
}
