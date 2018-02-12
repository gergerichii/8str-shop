<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 14.01.2018
 * Time: 11:38
 */

namespace common\behaviors;

use dosamigos\transliterator\TransliteratorHelper;
use yii\base\Behavior;

/**
 * Behavior for slug
 * @property string $slug
 */
class SlugBehavior extends Behavior {

    /**
     * Slug field
     * @var string
     */
    public $slugField = 'name';

    /**
     * Get slug
     * @return string
     */
    public function getSlug() {
        $ret = preg_replace('#[\s,]#', '_', TransliteratorHelper::process($this->owner->{$this->slugField}));
        return str_replace("'", 'i', $ret);
    }

}
