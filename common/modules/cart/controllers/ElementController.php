<?php
namespace common\modules\cart\controllers;

use common\modules\cart\widgets\ElementsList;
use yii\helpers\Json;
use yii\filters\VerbFilter;
use yii;
use yii\web\Controller;

class ElementController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionDelete()
    {
        $json = ['action' => 'delete', 'result' => 'undefined', 'error' => false];
        $elementId = yii::$app->request->post('elementId');

        $cart = yii::$app->get('cartService');

        $elementModel = $cart->getElementById($elementId);
        $json['elementName'] = $elementModel->getName();

        if($cart->deleteElement($elementModel)) {
            $json['result'] = 'success';
        }
        else {
            $json['result'] = 'fail';
        }

        return $this->_cartJson($json);
    }

    public function actionCreate()
    {
        $json = ['action' => 'create', 'result' => 'undefined', 'error' => false];

        /** @var \common\modules\cart\CartService $cartService */
        $cartService = yii::$app->get('cartService');

        $postData = yii::$app->request->post();

        $model = $postData['CartElement']['model'];
        if($model) {
            $productModel = new $model();
            $productModel = $productModel::findOne($postData['CartElement']['item_id']);

            $options = [];
            if(isset($postData['CartElement']['options'])) {
                $options = $postData['CartElement']['options'];
            }

            if($postData['CartElement']['price'] && $postData['CartElement']['price'] != 'false') {
                $elementModel = $cartService->putWithPrice($productModel, $postData['CartElement']['price'], $postData['CartElement']['count'], $options);
            } else {
                $elementModel = $cartService->put($productModel, $postData['CartElement']['count'], $options);
            }

            $json['elementId'] = $elementModel->getId();
            $json['elementName'] = $elementModel->getName();
            $json['result'] = 'success';
        } else {
            $json['result'] = 'fail';
            $json['error'] = 'empty model';
        }

        return $this->_cartJson($json);
    }

    public function actionUpdate()
    {
        $json = ['action' => 'update', 'result' => 'undefined', 'error' => false];

        $cartService = yii::$app->get('cartService');
        
        $postData = yii::$app->request->post();

        /** @var \common\modules\cart\models\CartElement $elementModel */
        $elementModel = $cartService->getElementById($postData['CartElement']['id']);
        
        if(isset($postData['CartElement']['count'])) {
            $elementModel->setCount($postData['CartElement']['count'], true);
        }
        
        if(isset($postData['CartElement']['options'])) {
            $elementModel->setOptions($postData['CartElement']['options'], true);
        }
        
        $json['element_cost'] = yii::$app->formatter->asCurrency($elementModel->getCost(false));
        $json['elementId'] = $elementModel->getId();
        $json['elementName'] = $elementModel->getName();
        $json['result'] = 'success';

        return $this->_cartJson($json);
    }

    private function _cartJson($json)
    {
        /** @var \common\modules\cart\CartService $cartModel */
        if ($cartModel = yii::$app->get('cartService')) {
            if(!$elementsListWidgetParams = yii::$app->request->post('elementsListWidgetParams')) {
                $elementsListWidgetParams = [];
            }

            $json['elementsHTML'] = ElementsList::widget($elementsListWidgetParams);
            $json['count'] = $cartModel->getCount();
            $json['clear_price'] = $cartModel->getCost(false);
            $json['price'] = $cartModel->getCostFormatted();
        } else {
            $json['count'] = 0;
            $json['price'] = 0;
            $json['clear_price'] = 0;
        }
        return Json::encode($json);
    }

}
