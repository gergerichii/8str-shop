<?php

namespace common\modules\files\models;

use GuzzleHttp\Client;
use yii\base\Model;
use yii\helpers\FileHelper;

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
        return \Yii::$app->urlManager->createAbsoluteUrl(['/files/default/download', 'entityName' => $this->entityName, 'fileName' => $this->fileName], $scheme);
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
            $this->addError('', 'The file ' . $this->getFilename() . ' already exists.');
            return false;
        }

        $this->createDirectory();

        $result = copy($oldName, $this->getFilename());
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
            $this->addError('', 'The file ' . $this->getFilename() . ' already exists.');
            return false;
        }

        $this->createDirectory();

        try {
            $client = new Client();
            $client->request('GET', $url, ['sink' => $this->getFilename()]);
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
            return FileHelper::createDirectory($this->getPath());
        } catch (\Exception $exception) {
            $this->addError('', $exception->getMessage());
            return false;
        }
    }
}