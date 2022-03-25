<?php


class AdminTokenModel extends \models\Database
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_token';
    }

    const LOGIN_TYPE_WEB = 0;
    const LOGIN_TYPE_API = 1;

    const TOKEN_EXPIRES_SECOND = 1 * 86400;

    /**
     * @param int $user_id
     * @param string $phone
     * @param int $login_type
     * @return mixed
     */
    public static function setToken(int $user_id, string $phone, int $login_type): mixed
    {
        $time       = time();
        $data       = [
            'admin_id'    => $user_id,
            'token'      => self::getToken($phone),
            'login_type' => $login_type,
            'expires_in' => $time + static::TOKEN_EXPIRES_SECOND,
            'updated_at' => $time
        ];
        if (self::has([ 'admin_id' => $user_id, 'login_type' => $login_type ])) {
            $re = self::update($data, [ 'admin_id' => $user_id, 'login_type' => $login_type ]);
        } else {
            $data['created_at'] = $time;
            $re                 = self::insert($data);
        }

        unset($data['admin_id'], $data['created_at'], $data['updated_at']);

        return $re ? $data : false;
    }

    /**
     * 获取用户
     */
    public static function getUser(string $token)
    {
        $usreTable = AdminModel::tableName();

        $user      = self::getDb()->select( 'admin_token',
            [ '[>]' . $usreTable => [ 'admin_id' => 'id' ] ],
            [
                $usreTable . '.id',
                $usreTable . '.username',
                $usreTable . '.name',
                $usreTable . '.phone',
                $usreTable . '.avatar',
                $usreTable . '.status',
                $usreTable . '.created_at',
                $usreTable . '.updated_at',
                'admin_token.expires_in'
            ],
            [ 'admin_token.token' => $token ]);


        if ($user)
            return $user[0];
        return false;
    }

    /**
     * 设置登录token  唯一性
     * @param string $user_phone
     * @return string
     */
    public static function getToken(string $user_phone): string
    {
        $str = md5(uniqid(md5(microtime(true)), true));
        return sha1($str . $user_phone);
    }
}