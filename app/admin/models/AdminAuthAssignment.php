<?php

class AdminAuthAssignmentModel extends \models\Database
{
    public static function tableName()
    {
        return "admin_auth_assignment";
    }

    public static function addItems($roles, $admin_id)
    {
        if (!$roles)
            return self::delete([ 'admin_id' => $admin_id ]);

        $t = time();
        foreach ($roles as $role) {
            if (!self::has([ 'admin_id' => $admin_id, 'item_name' => $role ])) {
                self::insert([ 'admin_id' => $admin_id, 'item_name' => $role, 'created_at' => $t ]);
            }
        }

        return true;
    }

    public static function getAdminRole($admin_id)
    {
        $sql = "SELECT admin_auth_item.`name`, admin_auth_item.description 
                FROM admin_auth_assignment INNER JOIN admin_auth_item ON admin_auth_assignment.item_name = admin_auth_item.`name` 
                WHERE admin_auth_assignment.admin_id = 1 AND admin_auth_item.type = 1";
        return self::getDb()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}