<?php

namespace common\modules\files\controllers;

use Yii;
use yii\base\InvalidConfigException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\modules\files\FilesModule as FileModule;

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
     *
     * @param string $entityType
     * @param string $fileName
     * @param bool   $isProtected
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDownload($entityType, $fileName, $isProtected = false) {
        /** @var FileModule $fileModule */
        $fileModule = $this->module;
        try {
            $entity = $fileModule->createEntity($entityType, $fileName);
        } catch (InvalidConfigException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }
        
        $allowDefault = true;
        $entity->isProtected = $isProtected;

        if (!$entity->exists($allowDefault)) {
            throw new NotFoundHttpException('Файл не существует!');
        }

        $response = Yii::$app->getResponse();
        $response->sendFile($entity->getFilePath($allowDefault), null, ['inline' => true]);
        $response->send();

        return false;
    }
}
