<?php

namespace common\modules\counters\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\counters\models\Counters;

/**
 * CountersSearch represents the model behind the search form of `common\modules\counters\models\Counters`.
 */
class CountersSearch extends Counters
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'position', 'created_at', 'created_by', 'modified_at', 'modified_by'], 'integer'],
            [['name', 'value', 'included_pages', 'excluded_pages'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = Counters::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'position' => $this->position,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'modified_at' => $this->modified_at,
            'modified_by' => $this->modified_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'value', $this->value])
            ->andFilterWhere(['like', 'included_pages', $this->included_pages])
            ->andFilterWhere(['like', 'excluded_pages', $this->excluded_pages]);

        return $dataProvider;
    }
}
