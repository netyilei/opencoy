<?php


namespace libs;


class Util
{
    static public function generate_code($length = 6)
    {
        $new_str = '';
        $str     = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwsyz0123456789';
        for ($i = 1; $i <= $length; ++$i) {
            $new_str .= $str[mt_rand(0, 61)];
        }
        return $new_str;
    }

    /**
     * 获取数组重复记录
     */
    static public function getArrayRepeat($data)
    {
        $unique_array = array_unique($data);
        $orderNoArray = array_diff_assoc($data, $unique_array);
        return array_values($orderNoArray);
    }

    static public function format_date($time = 0)
    {
        $t = time() - $time;
        if ($t < 60) {
            return '刚刚';
        }
        $f = [
            '31536000' => '年',
            '2592000'  => '个月',
            '604800'   => '星期',
            '86400'    => '天',
            '3600'     => '小时',
            '60'       => '分钟',
            '1'        => '秒'
        ];
        foreach ($f as $k => $v) {
            if (0 != $c = floor($t / (int) $k)) {
                return $c . $v . '前';
            }
        }
    }

    static public function format_money($money, $len = 2)
    {
        return sprintf("%.{$len}f", $money);
    }

    /**
     * 将字符串转换成二进制
     * @param $str
     * @return string
     */
    static public function strToBin($str)
    {
        //1.列出每个字符
        $arr = preg_split('/(?<!^)(?!$)/u', $str);
        //2.unpack字符
        foreach ($arr as &$v) {
            $temp = unpack('H*', $v);
            $v    = base_convert($temp[1], 16, 2);
            unset($temp);
        }

        return join(' ', $arr);
    }

    /**
     * 将二进制转换成字符串
     * @param $str
     * @return string
     */
    static public function binToStr($str)
    {
        $arr = explode(' ', $str);
        foreach ($arr as &$v) {
            $v = pack("H" . strlen(base_convert($v, 2, 16)), base_convert($v, 2, 16));
        }
        return join('', $arr);
    }
}