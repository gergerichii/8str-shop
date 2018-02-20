<?php

namespace common\modules\catalog\models;

use yii\base\Model;
use yii\sphinx\ActiveDataProvider;

/**
 * Class ProductSphinxSearch
 *
 * @author Andriy Ivanchenko <ivanchenko.andriy@gmail.com>
 */
class ProductSphinxSearch extends ProductSphinxIndex
{
    /**
     * @var string $q
     */
    public $q;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['q'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = ProductSphinxIndex::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->match('Foundnothingsodonotsearchanything.');
            return $dataProvider;
        }

        if (empty($this->q)) {
            $query->match('Foundnothingsodonotsearchanything.');
        } else {
            $query->match($this->q);
        }

        return $dataProvider;
    }
}