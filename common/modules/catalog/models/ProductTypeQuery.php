<?php

namespace common\modules\catalog\models;

use common\base\models\BaseDefaultQuery;

/**
 * This is the ActiveQuery class for [[ProductType]].
 *
 * @see ProductType
 */
class ProductTypeQuery extends BaseDefaultQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ProductType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ProductType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    
    /**
     * Эта функция должна задавать настройки для запроса по умолчанию
     */
    protected
    function _prepareQuery() {
        // TODO: Implement _prepareQuery() method.
    }
}
