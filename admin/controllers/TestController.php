<?php

namespace admin\controllers;

use common\models\entities\Product;
use common\models\entities\ProductQuery;
use common\models\entities\ProductTag;
use common\models\entities\ProductTagQuery;
use common\helpers\PriceHelper;
use common\helpers\ProductHelper;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class TestController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $tags = ProductTag::find()
            ->with(['products' => function(ProductQuery $q) {
                    $q->showOnHome();
            }])->usedAsGroup()->indexBy('name')->all();
        return $this->render('index');
    }

}
