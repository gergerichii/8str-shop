<?php

namespace common\modules\search\controllers;

use common\modules\catalog\models\Product;
use common\modules\catalog\models\ProductRubric;
use common\modules\catalog\providers\FrontendSearchProvider;
use yii\helpers\Url;
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
    
    /**
     * @param null $sc
     * @param null $sk
     *
     * @throws \yii\base\ErrorException
     */
    public function actionProcess($sc = null, $sk = null) {
        $productQuery = Product::find()->where([
            'or',
            ['[[product]].[[title]]' => $sk],
            ['[[product]].[[name]]' => $sk],
        ]);
        $catalogPath = '';
        /** @var \common\modules\catalog\Module $catalog */
        $catalog = \Yii::$app->getModule('catalog');
        if ($sc && $rubric = ProductRubric::findOne($sc)) {
            $rubrics = $rubric->children()->select('id')->asArray()->column();
            array_unshift($rubrics, $rubric->id);
            $productQuery->joinWith('rubrics')
                ->andWhere([
                    'or',
                    ['[[product]].[[main_rubric_id]]' => $rubrics],
                    ['[[product_rubric]].[[id]]' => $rubrics]
                ]);
            $catalogPath = $catalog->getRubricPath($rubric, false);
        }
        
        $products = $productQuery->all();
        if (count($products) === 1) {
            $this->redirect($catalog->getCatalogUri(null, $products[0]));
        } else {
            $params = compact('catalogPath', 'sc', 'sk');
            array_unshift($params, '/catalog/default/index');
            $this->redirect(Url::toRoute($params));
        }
    }
}
