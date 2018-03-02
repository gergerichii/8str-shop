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
     * @param bool $save
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function createThumbs($save = true) {
        $this->_thumbs = [];
        foreach ($this->thumbsOptions as $thumbName => $entityName) {
            /** @var Module $filesManagers */
            $filesManagers = \Yii::$app->getModule('files');
            $thumb = $filesManagers->createEntity($entityName, $this->fileName);

            if ($save && !$thumb->exists()) {
                $this->saveThumb($thumb);
            }

            $this->_thumbs[$thumbName] = $thumb;
        }
    }

    /**
     * Save thumb
     * @param Thumb $thumb
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function saveThumb(Thumb $thumb) {
        $thumb->createDirectory();

        /** @var ImageDriver $imageComponent */
        $imageComponent = \Yii::$app->get('image');
        /** @var Image_GD $image */
        $image = $imageComponent->load($this->getFilename());
        $image->resize($thumb->width, $thumb->height, $thumb->resizingConstrait);
        return $image->save($thumb->getFilename());
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