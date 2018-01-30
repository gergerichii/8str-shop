<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 16.01.2018
 * Time: 16:24
 */

namespace common\models\entities;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * Class BaseDefaultQuery
 *
 * @package common\models\entities
 *
 *
 */
abstract class BaseDefaultQuery extends ActiveQuery
{
    /** @var bool Показывает, показывается ли модель на фронт энде */
    public $forFrontEnd = false;

    /**
     * Эта функция должна задавать настройки для запроса по умолчанию
     */
    abstract protected function _prepareQuery();
    /**
     * @inheritdoc
     * @return ProductPriceDiscount[]|array
     */
    public function all($db = null)
    {
        $this->_prepareQuery();
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ProductPriceDiscount|array|null
     */
    public function one($db = null)
    {
        $this->_prepareQuery();
        return parent::one($db);
    }

    public function forFrontEnd() {
        $this->forFrontEnd = true;
        return $this;
    }
}