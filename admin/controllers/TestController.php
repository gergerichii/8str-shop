<?php

namespace admin\controllers;

use common\modules\catalog\models\queries\ProductQuery;
use common\modules\catalog\models\ProductTag;

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
    
    public function actionGrid() {
        
        return $this->render('grid');
    }

    
    public function actionTabsTest() {
        return $this->render('tabsTest');
    }
}
