<?php
namespace common\modules\cart\controllers;

use common\modules\cart\CartService;
use common\modules\cart\widgets\ElementsList;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii;
use yii\web\Controller;

class DefaultController extends Controller
{
    /** @var \common\modules\cart\CartService */
    protected $cart;
    
    public function __construct(string $id, Module $module, array $config = []) {
        $this->cart = yii::$app->get('cartService');
        parent::__construct($id, $module, $config);
    }
    
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'truncate' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        
        $elements = $this->cart->elements;

        return $this->render('index', [
            'elements' => $elements,
        ]);
    }
    
    /**
     * @return string
     * @throws \Exception
     */
    public function actionTruncate()
    {
        $json = ['result' => 'undefined', 'error' => false];
        
        $cartModel = $this->cart;
        
        if ($cartModel->truncate()) {
            $json['result'] = 'success';
        } else {
            $json['result'] = 'fail';
            $json['error'] = $cartModel->getCart()->getErrors();
        }
    
        return $this->_cartJson($json);
    }
    
    /**
     * @return string
     * @throws \Exception
     */
    public function actionInfo() {
        return $this->_cartJson();
    }
    
    /**
     * @param array $json
     *
     * @return string
     * @throws \Exception
     */
    private function _cartJson($json = [])
    {
        /** @var \common\modules\cart\CartService $cartModel */
        if ($cartModel = yii::$app->get('cartService')) {
            $json['elementsHTML'] = ElementsList::widget();
            $json['count'] = $cartModel->getCount();
            $json['price'] = $cartModel->getCostFormatted();
        } else {
            $json['count'] = 0;
            $json['price'] = 0;
        }
        return Json::encode($json);
    }
}
