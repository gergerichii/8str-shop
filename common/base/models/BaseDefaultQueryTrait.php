<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 22.05.2018
 * Time: 19:53
 */

namespace common\base\models;
trait BaseDefaultQueryTrait {
    /** @var bool Показывает, показывается ли модель на фронт энде */
    public $forFrontEnd = false;

    /**
     * Эта функция должна задавать настройки для запроса по умолчанию
     */
    abstract protected function _prepareQuery();
    
    /**
     * @param $models
     *
     * @return mixed
     */
    protected function _prepareModels($models) {
        return $models;
    }
    
    /**
     * @inheritdoc
     */
    public function all($db = null)
    {
        $this->_prepareQuery();
        return $this->_prepareModels(parent::all($db));
    }

    /**
     * @inheritdoc
     */
    public function one($db = null)
    {
        $this->_prepareQuery();
        return $this->_prepareModels(parent::one($db));
    }

    public function forFrontEnd() {
        $this->forFrontEnd = true;
        return $this;
    }
}