<?php

namespace common\modules\counters\models;
use common\base\models\{
    BaseDefaultQuery
};

/**
 * This is the ActiveQuery class for [[Counters]].
 *
 * @see Counters
 */
class CountersQuery extends BaseDefaultQuery
{
    public function active()
    {
        return $this->andWhere('[[active]]=1');
    }

    /**
     * @inheritdoc
     * @return Counters[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Counters|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    
    /**
     * Эта функция должна задавать настройки для запроса по умолчанию
     */
    protected function _prepareQuery() {
    
    }
}
