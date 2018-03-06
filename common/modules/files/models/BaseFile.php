<?php

namespace common\modules\files\models;

use common\modules\files\Module as FilesModule;
use GuzzleHttp\Client;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\FileHelper;

/**
 * Class BaseFile
 *
 * @property int $size
 */
abstract class BaseFile extends Model
{
    /**
     * Entity name
     * @var string
     */
    public $entityType;
    /**
     * @var bool
     */
    public $isProtected = false;
    /**
     * File name
     * @var string
     */
    public $fileName;
    /**
     * Default image
     * @var string
     */
    public $defaultFile = 'default.jpg';
    /**
     * @var \common\modules\files\Module
     */
    public $filesManager;
    
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init() {
        parent::init();
        
        if (empty($this->filesManager)) {
            if (!$this->filesManager = \Yii::$app->getModule('files')) {
                throw new InvalidConfigException('Files manager needed!');
            }
        }
    }
    
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['entityType', 'filename'], 'required'],
        ];
    }
    
    /**
     * Get uri
     * @param bool $scheme
     * @return string
     */
    public function getUri($scheme = false, $allowDefault = false) {
        $ret = \Yii::$app->urlManager->createAbsoluteUrl(
            [
                "/{$this->filesManager->defaultUri}/download",
                'entityType' => $this->entityType,
                'fileName' => $this->getBasename($allowDefault),
                'isProtected' => $this->isProtected
            ],
            $scheme
        );
        return str_replace('%2F', urldecode('%2F'), $ret);
    }
    
    /**
     * Get base name
     *
     * @param bool $allowDefault
     *
     * @return string
     */
    public function getBasename($allowDefault = false) {
        if ($this->exists()) {
            return $this->fileName;
        } elseif($allowDefault) {
            return $this->defaultFile;
        }
        
        return false;
    }
    
    /**
     * Get file path
     *
     * @param bool $allowDefault
     *
     * @param bool $checkExists
     *
     * @return string
     */
    public function getFilePath($allowDefault = false, $checkExists = false) {
        $pathField = $this->isProtected ? 'protectedPath' : 'publicPath';
        $path = $this->filesManager->$pathField;
        $path = \Yii::getAlias($path . DIRECTORY_SEPARATOR . $this->entityType . DIRECTORY_SEPARATOR);
        $filePath = $path . $this->fileName;
        if ($checkExists && (!$this->fileName || !file_exists($filePath))) {
            if ($allowDefault && $this->defaultFile) {
                $filePath = $path . $this->defaultFile;
                if (!file_exists($filePath)) {
                    $filePath = false;
                }
            } else {
                $filePath = false;
            }
        }
        
        return $filePath;
    }
    
    /**
     * Get size
     *
     * @param bool $allowDefault
     *
     * @return int
     */
    public function getSize($allowDefault = false) {
        if ($path = $this->getFilePath($allowDefault, true)) {
            return filesize($path);
        }
        return false;
    }
    
    /**
     * Whether to exists of file
     *
     * @param bool $allowDefault
     *
     * @return bool
     */
    public function exists($allowDefault = false) {
        return (boolean)$this->getFilePath($allowDefault, true);
    }

    /**
     * Delete file
     */
    public function delete() {
        if ($this->exists()) {
            unlink($this->getFilePath());
        }
    }

    /**
     * Pick the file from path
     * @param string $path
     * @return bool
     */
    public function pickFrom($path) {
        $oldName = $path . DIRECTORY_SEPARATOR . $this->fileName;
        if (!file_exists($oldName)) {
            $this->addError('', 'No file to move.');
            return false;
        }

        if ($this->exists()) {
            $this->addError('', 'The file ' . $this->getFilePath() . ' already exists.');
            return false;
        }

        $this->createDirectory();

        $result = copy($oldName, $this->getFilePath());
        if (false === $result) {
            $this->addError('', 'Unknown error!');
            return false;
        }

        return true;
    }

    /**
     * Pick the image from remote url
     * @param string $url
     * @return bool
     */
    public function pickFromRemote($url) {
        if ($this->exists()) {
            $this->addError('', 'The file ' . $this->getFilePath() . ' already exists.');
            return false;
        }

        $this->createDirectory();

        try {
            $client = new Client();
            $client->request('GET', $url, ['sink' => $this->getFilePath()]);
        } catch (\Exception $exception) {
            $this->addError('', $exception->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Make sure the directory exists
     * @return bool
     */
    public function createDirectory() {
        try {
            return FileHelper::createDirectory(dirname($this->getFilePath()));
        } catch (\Exception $exception) {
            $this->addError('', $exception->getMessage());
            return false;
        }
    }
}