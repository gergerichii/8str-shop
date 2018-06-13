<?php

namespace common\modules\news\controllers;

use common\modules\files\components\FilesManager;
use common\modules\news\Module;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `news` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex() {
        /** @var Module $newsModule */
        $newsModule = $this->module;
        $provider = $newsModule->getProviderOfArticles();
        if (0 >= $provider->getTotalCount()) {
            throw new NotFoundHttpException();
        }

        /** @var FilesManager $filesManager */
        $filesManager = \Yii::$app->getModule('files')->manager;

        return $this->render('index', ['provider' => $provider, 'newsModule' => $newsModule, 'filesManager' => $filesManager]);
    }
}
