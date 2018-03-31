<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 24.01.2018
 * Time: 11:30
 */

namespace common\helpers;

use yii\helpers\StringHelper;

class StringsHelper extends StringHelper {
    
    /**
     * @param string $a
     * @param string $b
     * @param bool   $trim
     *
     * @return string
     */
    static function strXor(string $a, string $b, bool $trim = TRUE) {
        $ret = '';
        $maxCount = max(strlen($a), strlen($b));
        for($i = 0; $i < $maxCount; $i++) {
            if(isset($a[$i]) && isset($b[$i])) {
                $ret[$i] = ($a[$i] == $b[$i]) ? ' ' : $a[$i];
            } else {
                $ret[$i] = (isset($a[$i])) ? $a[$i] : $b[$i];
            }
        }
        $ret = (string)$ret;
        if($trim) {
            $ret = trim($ret);
        }
        
        return $ret;
    }
    
    /**
     * @param $format
     *
     * @return string
     */
    static function mb_sprintf($format) {
        $argv = func_get_args();
        array_shift($argv);
        
        return self::mb_vsprintf($format, $argv);
    }
    
    /**
     * Works with all encodings in format and arguments.
     * Supported: Sign, padding, alignment, width and precision.
     * Not supported: Argument swapping.
     *
     * @param      $format
     * @param      $argv
     * @param null $encoding
     *
     * @return string
     */
    static function mb_vsprintf($format, $argv, $encoding = NULL) {
        if(is_null($encoding)) {
            $encoding = mb_internal_encoding();
        }
        // Use UTF-8 in the format so we can use the u flag in preg_split
        $format = mb_convert_encoding($format, 'UTF-8', $encoding);
        $newformat = ""; // build a new format in UTF-8
        $newargv = array(); // unhandled args in unchanged encoding
        while($format !== "") {
            
            // Split the format in two parts: $pre and $post by the first %-directive
            // We get also the matched groups
            $result = preg_split(
                "!\%(\+?)('.|[0 ]|)(-?)([1-9][0-9]*|)(\.[1-9][0-9]*|)([%a-zA-Z])!u",
                $format, 2, PREG_SPLIT_DELIM_CAPTURE
            );
            list ($pre, $sign, $filler, $align, $size, $precision, $type, $post) = (count($result) === 8)
                ? $result : array_fill(0, 8, NULL);
            $newformat .= mb_convert_encoding($pre, $encoding, 'UTF-8');
            if($type == '') {
                // didn't match. do nothing. this is the last iteration.
            } elseif($type == '%') {
                // an escaped %
                $newformat .= '%%';
            } elseif($type == 's') {
                $arg = array_shift($argv);
                $arg = mb_convert_encoding($arg, 'UTF-8', $encoding);
                $padding_pre = '';
                $padding_post = '';
                // truncate $arg
                if($precision !== '') {
                    $precision = intval(substr($precision, 1));
                    if($precision > 0 && mb_strlen($arg, $encoding) > $precision) {
                        $arg = mb_substr($precision, 0, $precision, $encoding);
                    }
                }
                // define padding
                if($size > 0) {
                    $arglen = mb_strlen($arg, $encoding);
                    if($arglen < $size) {
                        if($filler === '') {
                            $filler = ' ';
                        } elseif(strpos($filler, '\'') === 0) {
                            $filler = $filler[1];
                        }
                        if($align == '-') {
                            $padding_post = str_repeat($filler, $size - $arglen);
                        } else {
                            $padding_pre = str_repeat($filler, $size - $arglen);
                        }
                    }
                }
                // escape % and pass it forward
                $newformat .= $padding_pre . str_replace('%', '%%', $arg) . $padding_post;
            } else {
                // another type, pass forward
                $newformat .= "%$sign$filler$align$size$precision$type";
                $newargv[] = array_shift($argv);
            }
            $format = strval($post);
        }
        // Convert new format back from UTF-8 to the original encoding
        $newformat = mb_convert_encoding($newformat, $encoding, 'UTF-8');
        
        return vsprintf($newformat, $newargv);
    }
}