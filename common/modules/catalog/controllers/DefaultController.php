<?php

namespace common\modules\catalog\controllers;

use common\base\BaseFrontendController;
use common\models\entities\Product;
use common\modules\catalog\Module;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `catalog` module
 */
class DefaultController extends BaseFrontendController
{
    /**
     * @param string $catalogPath
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\base\ErrorException
     */
    public function actionIndex($catalogPath = '') {
        /** @var Module $catalog */
        $catalog = $this->module;
        $query = Product::find()->forFrontEnd()->with('rubrics');
        if ($catalogPath) {
            $rubric = $catalog->getRubricByPath($catalogPath);
            if (!$rubric) {
                throw new NotFoundHttpException('Путь не найден');
            }

            $rubricsIds = array_keys(
                $rubric->children()->select('id')->asArray()->indexBy('id')->all()
            );
            $rubricsIds[$rubric->id] = $rubric->id;
            $query->select('product.*')->distinct()
                ->joinWith('rubrics r', false)
                ->joinWith('mainRubric mr', false)
                ->andWhere(['or',
                    ['in', 'r.id', $rubricsIds],
                    ['in', 'mr.id', $rubricsIds],
                ]);
        }

        $productsDataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->addBreadcrumbs($catalog->getBreadcrumbs($catalogPath));

        return $this->render('index', compact('catalogPath', 'productsDataProvider'));
    }

    /**
     * @param string $catalogPath
     * @param        $productId
     *
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     */
    public function actionProduct($catalogPath = '', $productId = null) {
        $product = Product::find()->forFrontEnd()->where(['id' => $productId])->one();
        /** @var Module $catalog */
        $catalog = \Yii::$app->get('catalog');

        if (!$catalog->productHasRubric($product, $catalogPath)) {
            throw new NotFoundHttpException('Продукт не найден');
        }

        $this->addBreadcrumbs($catalog->getBreadcrumbs($catalogPath, $product));

        $this->view->title = (string) $product;
        $params = [
            'productModel' => $product,
            'catalogPath' => $catalogPath,
        ];

        return $this->render('product', $params);
    }
}
