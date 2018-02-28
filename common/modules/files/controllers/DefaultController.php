<?php

namespace common\modules\files\controllers;

use Yii;
use yii\base\InvalidConfigException;
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
     * @param string $entityName
     * @param string $fileName
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDownload($entityName, $fileName) {
        /** @var FileModule $fileModule */
        $fileModule = $this->module;
        try {
            $entity = $fileModule->createEntity($entityName, $fileName);
        } catch (InvalidConfigException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }


        if (!$entity->exists()) {
            throw new NotFoundHttpException('Файл не существует!');
        }

        $response = Yii::$app->getResponse();
        $response->sendFile($entity->getFilename(), null, ['inline' => true]);
        $response->send();

        return false;
    }
}
