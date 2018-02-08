<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 08.02.2018
 * Time: 17:39
 */

namespace common\i18n;
use yii\helpers\ArrayHelper;

class PhpMessageSource extends \yii\i18n\PhpMessageSource {
    public function getMessageFilePath($category, $language) {
        $ret = [];
        foreach ((array) $this->basePath as $path) {
            $messageFile = \Yii::getAlias($path) . "/$language/";
            if (isset($this->fileMap[$category])) {
                $messageFile .= $this->fileMap[$category];
            } else {
                $messageFile .= str_replace('\\', '/', $category) . '.php';
            }
            $ret[] = $messageFile;
        }
        return $ret;
    }
    
    public function loadMessagesFromFile($messageFiles) {
        $ret = [];
        foreach ((array) $messageFiles as $messageFile) {
            $ret = ArrayHelper::merge($ret, (array)parent::loadMessagesFromFile($messageFile));
        }
        
        return empty($ret) ? null : $ret;
    }
}