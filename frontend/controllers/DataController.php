<?php

namespace frontend\controllers;

use common\modules\catalog\models\Product;
use common\modules\catalog\models\ProductBrand;
use common\modules\catalog\models\ProductRubric;
use common\modules\catalog\Module;
use common\modules\catalog\providers\FrontendSearchProvider;
use common\modules\files\Module as FilesModule;
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

        $productsItems = array_map(function ($product) use ($catalog) {
            $pictureName = isset($product->images[0]) ? $product->images[0] : 'default.jpg';
            $pictureSrc = FilesModule::getImageUri($pictureName);

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
        return array_merge($rubricsItems, $brandsItems, $productsItems);
    }
}
