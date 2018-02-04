<?php

namespace common\modules\catalog\models;

/**
 * This is the ActiveQuery class for [[ProductTag]].
 *
 * @see ProductTag
 */
class ProductTagQuery extends BaseDefaultQuery
{
    /**
     * @return $this
     */
    public function showedOnProduct() {
        return $this->andWhere(['show_on_product' => TRUE]);
    }

    /**
     * @return $this
     */
    public function usedAsGroup() {
        return $this->andWhere(['>', 'use_as_group', 0])->orderBy(['use_as_group' => SORT_ASC]);
    }

    /**
     * @return $this
     */
    public function active() {
        return $this->andWhere(['status' => ProductTag::STATUS['SHOWED']]);
    }

    /**
     * Эта функция должна задавать настройки для запроса по умолчанию
     */
    protected function _prepareQuery(){
        if (!$this->forFrontEnd) return;
        $this->active();
    }
}