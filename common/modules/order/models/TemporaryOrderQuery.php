<?php

namespace common\modules\order\models;

/**
 * This is the ActiveQuery class for [[TemporaryOrder]].
 *
 * @see TemporaryOrder
 */
class TemporaryOrderQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TemporaryOrder[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TemporaryOrder|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
