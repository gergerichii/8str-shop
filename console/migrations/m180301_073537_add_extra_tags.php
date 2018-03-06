<?php

use common\modules\catalog\models\ProductTag;
use yii\db\Migration;

/**
 * Class m180301_073537_add_extra_tags
 */
class m180301_073537_add_extra_tags extends Migration
{
    const TAGS = [
        'popular' => 'Популярные',
        'featured' => 'Рекомендованные',
    ];

    /**
     * @inheritdoc
     */
    public function safeUp() {
        foreach (self::TAGS as $name => $title) {
            $tag = new ProductTag([
                'name' => $name,
                'title' => $title,
                'status' => 1,
                'use_as_group' => 1,
                'show_on_product' => 0,
            ]);

            if (!$tag->save()) {
                var_dump($tag->getErrors());
                Yii::debug(var_export($tag->getErrors(), true));
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        foreach (self::TAGS as $name => $title) {
            $tag = ProductTag::find()->where(['name' => $name])->one();
            if (!$tag) {
                continue;
            }

            if (false === $tag->delete()) {
                return false;
            }
        }

        return true;
    }
}
