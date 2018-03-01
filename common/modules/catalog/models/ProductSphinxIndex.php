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
    /*public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name'
        ];
    }*/

    /**
     * @inheritdoc
     */
    /*public function rules() {
        return [
            [['id'], 'number'],
            [['name'], 'string']
        ];
    }*/

    /**
     * @inheritdoc
     */
    public static function indexName() {
        return 'product';
    }
}