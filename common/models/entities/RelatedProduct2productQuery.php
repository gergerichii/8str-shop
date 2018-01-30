<?php

namespace common\models\entities;

/**
 * This is the ActiveQuery class for [[RelatedProduct2product]].
 *
 * @see RelatedProduct2product
 */
class RelatedProduct2productQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return RelatedProduct2product[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return RelatedProduct2product|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
