<?php

use common\modules\catalog\models\ProductTag;
use yii\db\Migration;

/**
 * Class m180127_074819_create_standart_tags
 */
class m180127_074819_create_standard_tags extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tags = [
            'new' => 'Новинка',
            'bestseller' => 'Хит продаж',
            'promo' => 'Спецпредложение',
        ];
        foreach($tags as $name => $title) {
            $tag = new ProductTag([
                'name' => $name,
                'title' => $title,
                'status' => true,
                'use_as_group' => true,
                'show_on_product' => true,
            ]);
            if (!$tag->save()) {
                var_dump($tag->errors);
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('product_tag');

        return true;
    }
}
