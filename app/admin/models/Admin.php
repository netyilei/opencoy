<?php

class AdminModel extends \models\Database
{
    public static function tableName()
    {
        return "admin";
    }

    public static function accessCheck($admin_id, $child): bool
    {
        if ($admin_id == 1)
            return true;

        $sql = "SELECT admin_auth_item_child.child 
                FROM admin_auth_assignment INNER JOIN admin_auth_item_child ON admin_auth_assignment.item_name = admin_auth_item_child.parent 
                WHERE admin_auth_assignment.admin_id = {$admin_id} AND admin_auth_item_child.child = '{$child}'
                LIMIT 1";

        return (bool) self::getDb()->query($sql)->fetchAll();
    }
}