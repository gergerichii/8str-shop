<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 24.01.2018
 * Time: 11:30
 */

namespace common\helpers;
class StringsHelper{
    static function strXor(string $a, string $b, bool $trim = true) {
        $ret = '';
        $maxCount = max(strlen($a), strlen($b));
        for($i=0; $i<$maxCount; $i++) {
            if (isset($a[$i]) && isset($b[$i])) {
                $ret[$i] = ($a[$i] == $b[$i]) ? ' ' : $a[$i];
            } else {
                $ret[$i] = (isset($a[$i])) ? $a[$i] : $b[$i];
            }
        }
        $ret = (string) $ret;
        if ($trim) {
            $ret = trim($ret);
        }
        
        return $ret;
    }
}