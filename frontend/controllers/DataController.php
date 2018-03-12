<?php

namespace frontend\controllers;

use common\modules\catalog\models\Product;
use common\modules\catalog\models\ProductBrand;
use common\modules\catalog\models\ProductRubric;
use common\modules\catalog\Module;
use common\modules\catalog\providers\FrontendSearchProvider;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class DataController
 *
 * @author Andriy Ivanchenko <invanchenko.andriy@gmail.com>
 */
class DataController extends Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $res = parent::beforeAction($action);

        \Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        return $res;
    }

    /**
     * Search
     *
     * @return array
     */
    public function actionSearch()
    {
        $search = new FrontendSearchProvider();
        $search->q = \Yii::$app->getRequest()->get('q');
        $search->rubric = \Yii::$app->getRequest()->get('rubric');
        $search->top();
        $search->prepare();

        /** @var Module $catalog */
        $catalog = \Yii::$app->getModule('catalog');
        /** @var \common\modules\files\Module $filesManager */
        $filesManager = \Yii::$app->getModule('files');

        $rubricsItems = array_map(function ($rubric) use ($catalog) {
            /** @var ProductRubric $rubric */
            return [
                'id' => $rubric->id,
                'name' => 'rubric: ' . $rubric->name,
                'type' => 'rubric',
                'url' => $catalog->getCatalogUri($rubric),
                'icon' => $rubric->icon
            ];
        }, $search->getRubrics());

        $brandsItems = array_map(function ($brand) use ($catalog) {
            /** @var ProductBrand $brand */
            return [
                'id' => $brand->id,
                'name' => 'brand: ' . $brand->name,
                'logo' => $brand->logo,
                'type' => 'brand',
                'url' => $catalog->getBrandUri($brand)
            ];
        }, $search->getBrands());

        $productsItems = array_map(function ($product) use ($catalog, $filesManager) {
            $pictureName = isset($product->images[0]) ? $product->images[0] : 'default.jpg';
            $pictureSrc = $catalog->getProductThumbnailUri($pictureName, 'little');

            $template = $this->renderPartial('search/product', [
                'model' => $product,
                'catalog' => $catalog,
                'price' => $catalog->priceOf($product),
                'pictureSrc' => $pictureSrc
            ]);

            /** @var Product $product */
            return [
                'id' => $product->id,
                'name' => $product->name,
                'picture' => $product->mainImage,
                'type' => 'template',
                'template' => $template,
                'url' => $catalog->getCatalogUri(null, $product),
            ];
        }, $search->getProducts());

        // Merging the results
        return array_merge($productsItems, $brandsItems, $rubricsItems);
    }
}
