<?php

namespace common\modules\catalog\models;

/**
 * This is the ActiveQuery class for [[Product2ProductRubric]].
 *
 * @see Product2ProductRubric
 */
class Product2ProductRubricQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Product2ProductRubric[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Product2ProductRubric|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
