<?php

namespace common\modules\files\models;

use yii\base\Model;
use yii\helpers\Url;

/**
 * Class BaseFile
 */
abstract class BaseFile extends Model
{
    /**
     * Entity name
     * @var string
     */
    public $entityName;

    /**
     * Path
     * @var string
     */
    public $path;

    /**
     * File name
     * @var string
     */
    public $fileName;

    /**
     * Sub directory
     * @var string
     */
    public $subdir;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['entityName', 'path', 'filename', 'subdir'], 'required'],
        ];
    }

    /**
     * Get path
     * @return bool|string
     */
    public function getPath() {
        return \Yii::getAlias($this->path . DIRECTORY_SEPARATOR . $this->subdir);
    }

    /**
     * Get uri
     * @param bool $scheme
     * @return string
     */
    public function getUri($scheme = false) {
        $url = Url::to(['/files/default/download', 'filePath' => $this->subdir . DIRECTORY_SEPARATOR . $this->fileName], $scheme);
        return str_replace('%2F', urldecode('%2F'), $url);
    }

    /**
     * Get base name
     * @return string
     */
    public function getBasename() {
        return $this->fileName;
    }

    /**
     * Get filename
     * @return string
     */
    public function getFilename() {
        return $this->getPath() . DIRECTORY_SEPARATOR . $this->getBasename();
    }

    /**
     * Get size
     * @return int
     */
    public function getSize() {
        return filesize($this->getFilename());
    }

    /**
     * Whether to exists of file
     * @return bool
     */
    public function exists() {
        return file_exists($this->getFilename());
    }

    /**
     * Delete file
     */
    public function delete() {
        if ($this->exists()) {
            unlink($this->getFilename());
        }
    }
}