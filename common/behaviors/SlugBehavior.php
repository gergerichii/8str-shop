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
 *
 * @property string $slug
 */
class SlugBehavior extends Behavior {
    public $slugField = 'name';

    public function getSlug() {
        return preg_replace('#[\s,]#', '_', TransliteratorHelper::process($this->owner->{$this->slugField}));
    }
}