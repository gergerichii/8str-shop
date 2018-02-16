<?php

namespace common\modules\catalog\controllers\admin;

use Yii;
use common\modules\catalog\models\Product;
use common\modules\catalog\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * DefaultController implements the CRUD actions for Product model.
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulkdelete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Product #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Shows the tree manager for rubrics
     * @return string
     */
    public function actionRubrics()
    {
        return $this->render('rubrics');
    }

    /**
     * Creates a new Product model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Product();
        $db = Yii::$app->getDb();

        if ($request->isAjax) {
            /**
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new Product",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            }

            $transaction = $db->beginTransaction();
            $model->load($request->post());
            $model->save();

            if (!$model->hasErrors()) {
                $transaction->commit();
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new Product",
                    'content' => '<span class="text-success">Create Product success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            }

            $transaction->rollBack();
            return [
                'title' => "Create new Product",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

            ];
        }

        /**
         *   Process for non-ajax request
         */
        $transaction = $db->beginTransaction();
        $model->load($request->post());
        $model->save();

        if (!$model->hasErrors()) {
            $transaction->commit();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $transaction->rollBack();
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $db = Yii::$app->getDb();

        if ($request->isAjax) {
            /**
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            $transaction = $db->beginTransaction();
            if ($request->isGet) {
                return [
                    'title' => "Update Product #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }

            $model->load($request->post());
            $model->save();

            if (!$model->hasErrors()) {
                $transaction->commit();
                $model->refresh();
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Product #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            }

            $transaction->rollBack();
            return [
                'title' => "Update Product #" . $id,
                'content' => $this->renderAjax('update', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }

        /*
        *   Process for non-ajax request
        */
        $transaction = $db->beginTransaction();
        if ($model->load($request->post()) && $model->save()) {
            $transaction->commit();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $transaction->rollBack();
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Delete an existing Product model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->update(true, ['status' => Product::STATUS['HIDDEN']]);

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        }

        /**
         *   Process for non-ajax request
         */
        return $this->redirect(['index']);
    }

    /**
     * Delete multiple existing Product model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionBulkdelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }

    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
