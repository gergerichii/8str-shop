<?php

namespace common\modules\files\models;

/**
 * Class Thumb
 */
class Thumb extends BaseFile
{
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
     * Resizing constrait
     *
     * @var int $resizingConstraint
     */
    public $resizingConstraint;

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_merge(parent::rules(), [
            [['width', 'height'], 'required'],
            [['width', 'height'], 'integer'],
        ]);
    }
}