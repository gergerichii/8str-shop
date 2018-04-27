<?php

namespace common\modules\catalog\models\queries;

use common\base\models\BaseDefaultQuery;

/**
 * This is the ActiveQuery class for [[Product2ProductRubric]].
 *
 * @see Product2ProductRubric
 */
class Product2ProductRubricQuery extends BaseDefaultQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/
    /**
     *
     */
    public function _prepareQuery() {
        // TODO: Implement _prepareQuery() method.
    }
    
    /**
     * @inheritdoc
     * @return \common\modules\catalog\models\Product2ProductRubric[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\modules\catalog\models\Product2ProductRubric|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
