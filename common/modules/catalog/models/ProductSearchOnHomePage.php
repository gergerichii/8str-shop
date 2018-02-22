<?php

namespace common\modules\catalog\models;

use common\modules\catalog\providers\FrontendSearchProvider;
use yii\base\Model;

/**
 * Class ProductSearchOnHomePage
 *
 * @author Andriy Ivanchenko <ivanchenko.andriy@gmail.com>
 */
class ProductSearchOnHomePage extends Model
{
    /**
     * @var int
     */
    public $rubric;

    /**
     * @var string
     */
    public $q;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['q', 'string'],
            ['rubric', 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rubric' => 'Rubric',
            'q' => 'Query'
        ];
    }

    /**
     * Search
     */
    public function search()
    {
        $provider = new FrontendSearchProvider();
        $provider->q = $this->q;
        $provider->rubric = $this->rubric;

        return $provider;
    }
}