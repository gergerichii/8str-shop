<?php

namespace common\modules\catalog\models\search;

use common\modules\catalog\models\ProductSphinxIndex;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use common\modules\catalog\models\Product;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * ProductSearch represents the model behind the search form about `common\modules\catalog\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * ProductSearch constructor.
     *
     * @param array $config
     */
    public function __construct($config = []) {
        if (!is_bool($config) && !isset($config['loadDefaults'])) {
            $config['loadDefaults'] = false;
        }
        parent::__construct($config);
    }
    
    /**
     *
     */
    public function init() {
        $this->scenario = 'search';
        parent::init();
        
        $this->status = self::STATUS['ACTIVE'];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['brandName', 'rubricName'], 'safe'],
            ]
        );
//        return [
//            [['id', 'status', 'count', 'delivery_time', 'creator_id', 'modifier_id', 'product_type_id', 'brand_id', 'main_rubric_id', 'old_id', 'old_rubric_id'], 'integer'],
//            [['name', 'title', 'desc', 'show_on_home', 'on_list_top', 'market_upload', 'files', 'created_at', 'modified_at', 'model', 'vendor_code', 'barcode', 'warranty', 'delivery_days'], 'safe'],
//            [['brandName'], 'safe']
//        ];
    }
    
    /**
     * @return array
     */
    public function attributes() {
        return ArrayHelper::merge(
            parent::attributes(),
            [
                'brandName', 'rubricName'
            ]
        );
    }
    
    
    public function attributeLabels() {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'brandName' => 'Производитель',
                'rubricName' => 'Основная рубрика',
            ]
        );
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
     * @param $params
     *
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function search($params)
    {
        $query = self::find()
            ->indexBy('id')
            ->select(['product.*', 'pb.name brandName', 'mr.name rubricName'])
            ->joinWith(['brand pb'])->with('brand')
            ->joinWith(['mainRubric mr'])->with('mainRubric');
        
        $excludeFilter = [];
        
        if (!empty($params[$this->formName()]['name'])) {
            $productIndexQuery = ProductSphinxIndex::find()
                ->select('id');
//                ->where(['rubric_id' => $allChildRubrics]);
            $name = Yii::$app->sphinx->quoteValue(trim($params[$this->formName()]['name']));
            $name = str_replace('/', '\/', $name);
            $productIndexQuery->match(new Expression(':match', ['match' => "@(name){$name}"]));
    
            $productsIds = $productIndexQuery->limit(20000)->column();
            $query->andWhere(['[[product]].[[id]]' => $productsIds]);
            $excludeFilter = ['name'];
        }
        
        $formName = $this->formName();
        $filter = new ActiveDataFilter([
            'searchModel' => $this,
            'filterAttributeName' => $formName,
        ]);
        $defaultFilter = [];
        foreach($this->activeAttributes() as $value) {
            if (array_key_exists($value, $this->attributes) && !in_array($this->attributes[$value], [null, ''])) {
                $defaultFilter[$value] = $this->attributes[$value];
            }
        }
        $filter->load(array_merge([$formName => $defaultFilter], $params));
        
        if ($filterConditions = $filter->build()) {
            $replaces = [
                'brandName' => 'pb.name',
                'rubricName' => 'mr.name',
            ];
            $filterConditions = $this->prepareFilterCondition($filterConditions, $excludeFilter, $replaces);
            $query->andFilterWhere($filterConditions);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $sort = $dataProvider->getSort()->attributes;
        $sort['main_rubric_id'] = [
            'asc' => ['mr.name' => SORT_ASC],
            'desc' => ['mr.name' => SORT_DESC],
        ];
        $dataProvider->setSort([
            'attributes' => $sort,
        ]);
        
        
        return $dataProvider;
    }
    
    /**
     * @param       $conditions
     * @param array $excludeFilter
     *
     * @param array $replaces
     *
     * @return array
     */
    private function prepareFilterCondition($conditions, $excludeFilter = [], $replaces = []) {
        static $attributes;
        if (!isset($attributes)) $attributes = array_flip(parent::attributes());
        
        $ret = [];
        
        foreach($conditions as $key => $condition) {
            if(is_array($condition)) {
                $condition = $this->prepareFilterCondition($condition, $excludeFilter, $replaces);
            }
            if (is_string($key) && array_key_exists($key, $attributes)) {
                if (in_array($key, $excludeFilter)) continue;
                $key = "{$this->tableName()}.{$key}";
            }
            if ($condition === '0' || $condition === 0 || !empty($condition)) {
                if (isset($replaces[$key])){
                    $key = $replaces[$key];
                }
                $ret[$key] = $condition;
            }
        }
        
        return $ret;
    }
}
