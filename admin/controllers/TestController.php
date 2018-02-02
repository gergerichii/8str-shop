<?php

namespace admin\controllers;

use common\modules\catalog\models\Product;
use common\modules\catalog\models\ProductQuery;
use common\modules\catalog\models\ProductTag;
use common\modules\catalog\models\ProductTagQuery;
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
