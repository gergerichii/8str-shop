<?php
namespace common\modules\order\controllers\admin;

use yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
//            'access' => [
//                'class' => AccessControl::class,
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => $this->module->adminRoles,
//                    ]
//                ]
//            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
