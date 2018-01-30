<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 05.01.2018
 * Time: 18:14
 */

namespace common\helpers;


use yii\helpers\FileHelper;

class ProductHelper {
    public const VALID_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'gif', 'png'];

    public const IMAGES_PATH_ALIAS = '@common/web_files/catalog';

    public static function getImagesPath() {
        return \Yii::getAlias(self::IMAGES_PATH_ALIAS);
    }

    public static function getFileStorePart(string $file) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        return in_array($ext, self::VALID_IMAGE_EXTENSIONS) ? 'images' : 'files';
    }
}