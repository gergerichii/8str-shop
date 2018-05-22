<?php

namespace common\modules\catalog\models\queries;

use common\base\models\BaseDefaultQueryTrait;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[ProductTag2product]].
 *
 * @see ProductTag2product
 */
class ProductTag2productQuery extends ActiveQuery
{
    use BaseDefaultQueryTrait;
    
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\modules\catalog\models\ProductTag2product[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\modules\catalog\models\ProductTag2product|array|null
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
