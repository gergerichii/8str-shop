<?php

namespace common\modules\catalog\models;

use yii\sphinx\ActiveRecord;

/**
 * Class ProductSphinxIndex
 *
 * @author Andriy Ivanchenko <ivanchenko.andriy@gmail.com>
 *
 * @property int $id
 * @property string $name
 */
class ProductSphinxIndex extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function indexName() {
        return 'product';
    }
}