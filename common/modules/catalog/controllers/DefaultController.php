<?php

namespace common\modules\catalog\controllers;

use common\base\BaseFrontendController;
use common\modules\catalog\models\Product;
use common\modules\catalog\models\ProductBrandQuery;
use common\modules\catalog\Module;
use common\modules\catalog\providers\FrontendSearchProvider;
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
        $request = \Yii::$app->getRequest();
        $brandAlias = $request->get('brand');

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
                ->with('price')
                ->with('oldPrice')
                ->andWhere(['or',
                    ['in', 'r.id', $rubricsIds],
                    ['in', 'mr.id', $rubricsIds],
                ]);
        }

        if (isset($brandAlias)) {
            $query->joinWith(['brand' => function ($q) {
                /** @var ProductBrandQuery $q */
                $q->alias('brand');
            }]);

            $query->andWhere(['brand.alias' => $brandAlias]);
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
        $catalog = $this->module;

        if (!$product || !$catalog->productHasRubric($product, $catalogPath)) {
            throw new NotFoundHttpException('Продукт не найден');
        }

        $this->addBreadcrumbs($catalog->getBreadcrumbs($catalogPath, $product));

        $this->view->title = (string)$product;
        $params = [
            'productModel' => $product,
            'catalogPath' => $catalogPath,
        ];

        return $this->render('product', $params);
    }

    /**
     * Search
     */
    public function actionSearch() {
        $search = new FrontendSearchProvider();
        $search->q = \Yii::$app->getRequest()->get('q');
        $search->rubric = \Yii::$app->getRequest()->get('rubric');
        $search->top();

        return $this->render('search', [
            'provider' => $search
        ]);
    }
}
