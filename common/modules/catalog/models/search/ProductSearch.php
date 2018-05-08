<?php

namespace common\modules\catalog\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\catalog\models\Product;

/**
 * ProductSearch represents the model behind the search form about `common\modules\catalog\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'count', 'delivery_time', 'creator_id', 'modifier_id', 'product_type_id', 'brand_id', 'main_rubric_id', 'old_id', 'old_rubric_id'], 'integer'],
            [['name', 'title', 'desc', 'show_on_home', 'on_list_top', 'market_upload', 'files', 'created_at', 'modified_at', 'model', 'vendor_code', 'barcode', 'warranty', 'delivery_days'], 'safe'],
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
        $query = Product::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }
}
