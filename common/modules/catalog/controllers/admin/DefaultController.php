<?php

namespace common\modules\catalog\controllers\admin;

use common\modules\catalog\models\forms\ProductImagesForm;
use common\modules\catalog\models\ProductSearch;
use common\modules\files\models\Image;
use common\modules\files\Module;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * DefaultController implements the CRUD actions for Product model.
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete-image' => ['post'],
                    'upload-image' => ['post'],
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
    public function actionIndex() {
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
    public function actionView($id) {
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
    public function actionRubrics() {
        return $this->render('rubrics');
    }

    /**
     * Creates a new Product model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionCreate() {
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
    public function actionUpdate($id) {
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
    public function actionDelete($id) {
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
    public function actionBulkdelete() {
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
        }

        /*
        *   Process for non-ajax request
        */
        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * The action to show images of the product
     * @param int $id Product id
     * @return array|string
     * @throws NotFoundHttpException
     */
    public function actionImages($id) {
        $product = $this->findModel($id);

        $request = Yii::$app->request;
        $model = new ProductImagesForm();
        $model->id = $product->id;
        $model->product = $product;

        if ($request->isAjax) {
            /**
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                'title' => "Update Product Images #" . $id,
                'content' => $this->renderAjax('images', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        }

        return $this->render('images', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing prices of Product.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionPrices($id) {
        $request = Yii::$app->request;
        $product = $this->findModel($id);
        if (!$product) {
            throw new NotFoundHttpException();
        }

        $model = new ProductPricesForm();
        $model->id = $product->id;

        if ($request->isAjax) {
            /**
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Update prices o Product #" . $id,
                    'content' => $this->renderAjax('prices', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }

            $isLodaded = $model->load($request->post());
            if ($isLodaded) {
                $model->save();
            }

            if ($isLodaded && !$model->hasErrors()) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'forceClose' => true,
                    'forceReload' => 'true',
                ];
            }

            return [
                'title' => "Update Product #" . $id,
                'content' => $this->renderAjax('prices', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }

        /**
         *   Process for non-ajax request
         */
        $isLodaded = $model->load($request->post());
        if ($isLodaded) {
            $model->save();
        }

        if ($isLodaded && !$model->hasErrors()) {
            return $this->redirect(['catalog/default/index']);
        }

        return $this->render('prices', [
            'model' => $model,
        ]);
    }

    /**
     * FIXME: Это должно быть не тут. Это относится к файловому менеджеру. Или хотя бы частично туда перенести
     *
     * The action to upload an image
     * @return bool|string
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\base\ErrorException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function actionUploadImage() {
        $request = Yii::$app->getRequest();
        $productId = $request->post('id');
        $product = $this->findModel($productId);
        if (!$product) {
            return Json::encode([
                'error' => 'Product not found!'
            ]);
        }

        /** @var Module $filesManager */
        $filesManager = Yii::$app->getModule('files');

        $uploadedFiles = UploadedFile::getInstancesByName('ProductImagesForm[images]');
        $initialPreview = [];
        $initialPreviewConfig = [];
        $hasImages = false;
        foreach ($uploadedFiles as $uploadedFile) {
            try {
                /** @var Image $image */
                $image = $filesManager->createEntity('products/images', $uploadedFile->name);
            } catch (\Exception $exception) {
                $product->addError('uploadFiles', 'Error: ' . $exception->getMessage() . '.');
                return false;
            }

            $uploadedFile->saveAs($image->getFilePath());

            $image->getThumbs();
            $product->addFile($image->fileName);

            $initialPreview[] = $image->getUri(true);
            $initialPreviewConfig[] = [
                'caption' => $image->fileName,
                'width' => '120px',
                'url' => Url::to(['/catalog/default/delete-image']),
                'key' => $product->id,
                'extra' => [
                    'id' => $product->id,
                    'imageName' => $image->fileName
                ]
            ];
            $hasImages = true;
        }

        if ($hasImages) {
            $product->update();
        }

        return Json::encode([
            'error' => null,
            'initialPreview' => $initialPreview,
            'initialPreviewConfig' => $initialPreviewConfig,
            /*'initialPreviewThumbTags' => [
                [
                    '{CUSTOM_TAG_NEW}' => ' ',
                    '{CUSTOM_TAG_INIT}' => '<span class=\'custom-css\'>CUSTOM MARKUP</span>'
                ]
            ],*/
        ]);
    }

    /**
     * The action to delete an image
     * @return string
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteImage() {
        $request = Yii::$app->getRequest();
        //$pictureId = $request->post('key');
        $productId = $request->post('id');
        $imageName = $request->post('imageName');

        /** @var Module $filesManager */
        $filesManager = Yii::$app->getModule('files');
        /** @var Image $image */
        $image = $filesManager->createEntity('products/images', $imageName);
        $image->delete();

        $product = $this->findModel($productId);
        $product->deleteFile($imageName);
        $product->update();

        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        return Json::encode(true);
    }
}
