<?php

namespace common\modules\files\models;

use common\modules\files\Module;
use yii\image\drivers\Image as ExtImageDriver;
use yii\image\drivers\Image_GD;
use yii\image\ImageDriver;

/**
 * Class Image
 *
 * @property Thumb[]|array $thumbs
 */
class Image extends BaseFile
{
    /**
     * Thumbs options
     * @var array|null
     */
    public $thumbsOptions = null;

    /**
     * Old images subdir
     * @var string
     */
    public $oldImagesDir = null;
    
    /**
     * Width
     * @var int $width
     */
    public $width;

    /**
     * Heihght
     * @var int $height
     */
    public $height;
    
    /**
     * Thumbs collection
     * @var Thumb[]|null
     */
    private $_thumbs = null;

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_merge(parent::rules(), [
            [['thumbsOptions'], 'required']
        ]);
    }

    /**
     * Get thumb
     * @throws \yii\base\ErrorException
     * @throws \yii\base\InvalidConfigException
     * @return Thumb[]
     * @throws \yii\base\Exception
     */
    public function getThumbs() {
        if (is_null($this->_thumbs) && isset($this->thumbsOptions)) {
            $this->createThumbs(false);
        }

        return $this->_thumbs;
    }
    
    /**
     * Create thumbs
     *
     * @param bool $save
     * @param int $master
     *
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function createThumbs($save = true, $master = null) {
        $this->_thumbs = [];
        foreach ($this->thumbsOptions as $thumbName => $entityName) {
            /** @var Module $filesManagers */
            $filesManagers = \Yii::$app->getModule('files');
            $thumb = $filesManagers->createEntity($entityName, $this->fileName);

            if ($save && !$thumb->exists()) {
                $this->saveThumb($thumb, $master);
            }

            $this->_thumbs[$thumbName] = $thumb;
        }
    }
    
    /**
     * Save thumb
     *
     * @param Thumb $thumb
     * @param int  $master
     *
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function saveThumb(Thumb $thumb, $master = null) {
        $thumb->createDirectory();

        /** @var ImageDriver $imageComponent */
        $imageComponent = \Yii::$app->get('image');
        /** @var Image_GD $image */
        $image = $imageComponent->load($this->getFilename());
        $image->resize($thumb->width, $thumb->height, !is_null($master) ? $master : $thumb->resizingConstrait);
        return $image->save($thumb->getFilename());
    }
    
    /**
     * @param             $master
     *
     * @param string|null $saveAs
     *
     * @param bool        $force
     *
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function adaptSize($master, $saveAs = null, $force = false) {
        $this->clearErrors();
        /** @var ImageDriver $imageComponent */
        $imageComponent = \Yii::$app->get('image');
        /** @var Image_GD $image */
        $image = $imageComponent->load($this->getFilename());
        if ($force || ($image->width !== $this->width || $image->height !== $this->height)) {
            $image->resize($this->width, $this->height, $master);
            FileHelper::unlink($this->getFilename());
            if ($saveAs) {
                $this->fileName = $saveAs;
            }
            return $image->save($this->getFilename());
        } else {
            $this->addError('', "Do not need to resize {$this->getFilename()}");
            return false;
        }
    }
    
    public function toGrowOld() {
        $this->clearErrors();
        $oldImagesPath = $this->getPath() . DIRECTORY_SEPARATOR . $this->oldImagesDir;
        if (!is_dir($oldImagesPath)) {
            try{
                FileHelper::createDirectory($oldImagesPath);
            } catch(\Exception $e) {
                $this->addError('', "Don't create subdir {$oldImagesPath}! ({$e->getMessage()})");
                return false;
            }
        }
        if ($this->exists()) {
            $oldImagesPath = preg_match('#^[/@]#', $this->oldImagesDir)
                ? \Yii::getAlias($this->oldImagesDir) . DIRECTORY_SEPARATOR
                : $oldImagesPath . DIRECTORY_SEPARATOR;
            $result = rename($this->getFilename(), $oldImagesPath . $this->getBasename());
            if (false === $result) {
                $this->addError('', 'Unknown error!');
                return false;
            }
        } else {
            $this->addError('', "File {$this->getFilename()} not found to grow it old");
            return false;
        }
    }

    /**
     * Delete image
     */
    public function delete() {
        parent::delete();

        if ($this->thumbs) {
            foreach ($this->thumbs as $thumb) {
                $thumb->delete();
            }
        }
    }

    /**
     * Get thumb
     * @param string $entityName Thumb entity name
     * @return Thumb|null
     */
    public function getThumb($entityName) {
        if (array_key_exists($entityName, $this->thumbs)) {
            return $this->thumbs[$entityName];
        }

        return null;
    }
}