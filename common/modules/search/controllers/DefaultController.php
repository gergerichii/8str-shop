<?php

namespace common\modules\search\controllers;

use common\modules\catalog\providers\FrontendSearchProvider;
use yii\web\Controller;
use yii\web\Response;

/**
 * Default controller for the `search` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     *
     * @param null $q
     *
     * @param null $r
     *
     * @return array
     */
    public function actionIndex($q = null, $r = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $search = new FrontendSearchProvider();
        $search->q = $q;
        $search->rubric = $r;
        $search->top();
        $search->prepare();

        /** @var \common\modules\catalog\Module $catalog */
        $catalog = \Yii::$app->getModule('catalog');
        /** @var \common\modules\files\Module $filesManager */
        $filesManager = \Yii::$app->getModule('files');

        $rubricsItems = array_map(function ($rubric) use ($catalog) {
            /** @var \common\modules\catalog\models\ProductRubric $rubric */
            return [
                'id' => $rubric->id,
                'name' => $rubric->name,
                'type' => 'rubric',
                'url' => $catalog->getCatalogUri($rubric),
                'icon' => $rubric->icon
            ];
        }, $search->getRubrics());

        $brandsItems = array_map(function ($brand) use ($catalog) {
            /** @var \common\modules\catalog\models\ProductBrand $brand */
            return [
                'id' => $brand->id,
                'name' => $brand->name,
                'logo' => $brand->logo,
                'type' => 'brand',
                'url' => $catalog->getBrandUri($brand)
            ];
        }, $search->getBrands());

        $productsItems = array_map(function ($product) use ($catalog, $filesManager) {
//            $pictureName = isset($product->images[0]) ? $product->images[0] : 'default.jpg';
//            $pictureSrc = $catalog->getProductThumbnailUri($pictureName, 'little');

//            $template = $this->renderPartial('search/product', [
//                'model' => $product,
//                'catalog' => $catalog,
//                'price' => $catalog->priceOf($product),
//                'pictureSrc' => $pictureSrc
//            ]);

            /** @var \common\modules\catalog\models\Product $product */
            return [
                'id' => $product->id,
                'name' => $product->name,
                'picture' => $product->mainImage,
                'type' => 'template',
//                'template' => $template,
                'url' => $catalog->getCatalogUri(null, $product),
            ];
        }, $search->getProducts());

        // Merging the results
        return ['products' => $productsItems, 'brands' => $brandsItems, 'rubrics' => $rubricsItems];
    }
}
