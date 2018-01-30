<?php

namespace common\modules\files\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\modules\files\Module as FileModule;

/**
 * Default controller for the `file` module
 *
 *
 * TODO: Нужно автоматически определять контент файлов и брать файлы из нужных папок
 * TODO: Сделать контроллер upload который будет генерить тумбы и проставлять ватермарки
 */
class DefaultController extends Controller
{

    public $defaultAction = 'download';

    /**
     * Renders the index view for the module
     * @param $filePath
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDownload($filePath)
    {
        /** @var FileModule $fileModule */
        $fileModule = $this->module;
        $filePath = str_replace('../', '', $filePath);
        $fileRealPath = $fileModule->getFilePath($filePath);

        if (!file_exists($fileRealPath)) {
            throw new NotFoundHttpException('Файл не существует!');
        }

        $fileLocation = Yii::getAlias("@commonFilesUri/$filePath");
        yii::$app->response->xSendFile($fileLocation, null, [
            'xHeader' => 'X-Accel-Redirect',
            'inline' => true
        ]);
        yii::$app->response->send();

        return false;
    }
}
