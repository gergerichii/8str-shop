<?php

namespace common\modules\catalog\models;

/**
 * This is the ActiveQuery class for [[ProductBrand]].
 *
 * @see ProductBrand
 */
class ProductBrandQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ProductBrand[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ProductBrand|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
