<?php

namespace common\modules\catalog\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * ProductSearch represents the model behind the search form about `common\modules\catalog\models\Product`.
 */
class ProductSearch extends Product
{

    public $brandName;

    public $rubricName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'title', 'desc', 'created_at', 'modified_at'], 'safe'],
            [['brandName', 'rubricName'], 'safe']
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
        $query = Product::find()->alias('product');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['product.id' => $this->id]);
        $query->andFilterWhere(['like', 'product.name', $this->name]);

        $brandName = $this->brandName;
        $query->joinWith(['brand' => function ($query) use ($brandName) {
            /** @var ProductBrandQuery $query */
            $query->andFilterWhere(['like', 'brand.name', $brandName]);
            $query->alias('brand');
        }]);

        $rubricName = $this->rubricName;
        $query->joinWith(['rubrics' => function ($query) use ($rubricName) {
            /** @var ActiveQuery $query */
            $query->andFilterWhere(['like', 'rubrics.name', $rubricName]);
            $query->alias('rubrics');
        }]);

        return $dataProvider;
    }
}
