<?php
namespace common\modules\order\controllers\admin;

use yii;
use common\modules\order\models\tools\OrderSearch;
use common\modules\order\models\OrderElement;
use common\modules\order\models\Order;
use common\modules\order\models\Field;
use common\modules\order\models\tools\ElementSearch;
use common\modules\order\models\PaymentType;
use common\modules\order\models\ShippingType;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class OperatorController  extends Controller
{
    public function behaviors()
    {
        return [
//            'access' => [
//                'class' => AccessControl::class,
//				'only' => ['index'],
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => $this->module->operatorRoles,
//                    ]
//                ]
//            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        
        $searchParams = yii::$app->request->queryParams;
        
        $dataProvider = $searchModel->search($searchParams);

        $paymentTypes = ArrayHelper::map(PaymentType::find()->all(), 'id', 'name');
        $shippingTypes = ArrayHelper::map(ShippingType::find()->all(), 'id', 'name');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'shippingTypes' => $shippingTypes,
            'paymentTypes' => $paymentTypes,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionView($id)
    {
        $this->layout = '@vendor/shop/yii2-order/views/layouts/mini';
        
        $model = $this->findModel($id);
        
        if($model->status == $this->module->defaultStatus) {
            $model->status = $this->module->operatorOpenStatus;
            $model->seller_user_id = yii::$app->user->id;
            $model->save(false);
        }
        
        $searchModel = new ElementSearch;
        $params = yii::$app->request->queryParams;

        if(!is_array($params)) {
            $params = [];
        }
        
        $params['ElementSearch']['order_id'] = $model->id;

        $dataProvider = $searchModel->search($params);

        $paymentTypes = ArrayHelper::map(PaymentType::find()->all(), 'id', 'name');
        $shippingTypes = ArrayHelper::map(ShippingType::find()->all(), 'id', 'name');

        $fieldFind = Field::find();

        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'shippingTypes' => $shippingTypes,
            'fieldFind' => $fieldFind,
            'paymentTypes' => $paymentTypes,
            'model' => $model,
        ]);
    }
    
    protected function findModel($id)
    {
        $orderModel = new Order;
        
        if (($model = $orderModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested order does not exist.');
        }
    }
}