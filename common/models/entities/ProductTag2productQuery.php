<?php

namespace common\models\entities;

/**
 * This is the ActiveQuery class for [[ProductTag2product]].
 *
 * @see ProductTag2product
 */
class ProductTag2productQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ProductTag2product[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ProductTag2product|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
