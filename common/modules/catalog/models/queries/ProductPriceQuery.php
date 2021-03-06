<?php

namespace common\modules\catalog\models\queries;

use common\base\models\BaseDefaultQueryTrait;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[ProductPrice]].
 *
 * @see ProductPrice
 */
class ProductPriceQuery extends ActiveQuery
{
    use BaseDefaultQueryTrait;
    
    public $isRelated = false;

    /**
     * Populate relation
     * @param string $name
     * @param array $primaryModels
     *
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function populateRelation($name, &$primaryModels)
    {
        $this->isRelated = true;
        return parent::populateRelation($name, $primaryModels);
    }

    /**
     * For current domain
     * @return $this
     */
    public function forCurrentDomain(){
        $this->andWhere([
            'domain_name' => \Yii::$app->params['domains'][\Yii::$app->params['domain']],
        ]);

        return $this;
    }

    /**
     * Only active prices
     * @return $this
     */
    public function onlyActive()
    {
        return $this->andWhere(['<=', 'active_from', new Expression('NOW()')])
            ->andWhere('status="active"');
    }

    /**
     * Only active prices
     * @return $this
     */
    public function onlyInactive() {
        return $this->andWhere(['<=', 'active_from', new Expression('NOW()')])
            ->andWhere('status="inactive"');
    }

    /**
     * Only future prices
     * @return $this
     */
    public function onlyFuture()
    {
        return $this->andWhere(['>', 'active_from', new Expression('NOW()')])
            ->andWhere('status="active"');
    }

    /**
     * Эта функция должна задавать настройки для запроса по умолчанию
     */
    protected function _prepareQuery()
    {
        /*$this->active()->orderBy(['active_from' => SORT_DESC])->andWhere([
            'domain_name' => \Yii::$app->params['domains'][\Yii::$app->params['domain']],
        ]);
        if (!$this->isRelated && $this->forFrontEnd) {
            $this->limit(2);
        }*/
    }
}
