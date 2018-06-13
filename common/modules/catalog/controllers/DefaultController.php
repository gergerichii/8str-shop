<?php

namespace common\modules\catalog\controllers;

use common\base\BaseFrontendController;
use common\modules\catalog\models\forms\ProductFilterForm;
use common\modules\catalog\models\Product;
use common\modules\catalog\CatalogModule;
use common\modules\catalog\providers\FrontendSearchProvider;
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

        /** @var CatalogModule $catalog */
        $catalog = $this->module;

        $filterForm = new ProductFilterForm();
        $filterForm->load($request->get(), '');
        $filterForm->validate();

        $productsDataProvider = $filterForm->makeProductsProvider();

        $this->addBreadcrumbs($catalog->getBreadcrumbs($catalogPath));

        return $this->render('index', compact('catalogPath', 'productsDataProvider', 'filterForm'));
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
        /** @var CatalogModule $catalog */
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
    public function actionSearch($q = null, $r = null) {
        $search = new FrontendSearchProvider();
        $search->q = \Yii::$app->getRequest()->get('q');
        $search->rubric = \Yii::$app->getRequest()->get('rubric');
        $search->top();

        return $this->render('search', [
            'provider' => $search
        ]);
    }
}
