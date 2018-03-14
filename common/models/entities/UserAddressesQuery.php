<?php

namespace common\models\entities;

/**
 * This is the ActiveQuery class for [[UserAddresses]].
 *
 * @see UserAddresses
 */
class UserAddressesQuery extends \common\base\models\BaseDefaultQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return UserAddresses[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserAddresses|array|null
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
