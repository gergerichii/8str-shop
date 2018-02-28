<?php

namespace common\modules\files;

use common\modules\files\components\FileUrlRule;
use common\modules\files\models\BaseFile;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\web\UrlManager;

/**
 * Class Module for the Files
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'common\modules\files\controllers';

    /**
     * Entities
     * @var array
     */
    public $entities = [];

    /**
     * Entities instances
     * @var array
     */
    private $_entityInstances = [];

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        // custom initialization code goes here
    }

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app) {
        $urlManagers = [];
        foreach (array_keys($app->components) as $componentName) {
            if (strPos($componentName, 'UrlManager') > 0)
                $urlManagers[] = $componentName;
        }

        $rules = [
            [
                'class' => FileUrlRule::class,
            ],
            /*[
                'name' => 'fileRule',
                'pattern' => 'files/<_a:download|upload|.{0}>/<filePath:[\w\-\.,/_-]*>',
                'route' => 'files/default/<_a>',
                'defaults' => [
                    'filePath' => '',
                    '_a' => 'download',
                ]
            ]*/
        ];

        if (count($urlManagers)) {
            foreach ($urlManagers as $urlManager) {
                /** @var UrlManager $urlManager */
                $urlManager = $app->get($urlManager);
                $urlManager->addRules($rules);
            }
        } else {
            $app->urlManager->addRules($rules);
        }
    }

    /**
     * Get the path of file
     * @param string $entityName
     * @param string $filename
     * @return string
     * @throws InvalidConfigException
     */
    public function getFilePath(string $entityName, string $filename) {
        $entity = $this->getEntityInstance($entityName);
        $entity->fileName = $filename;
        $ret = \Yii::$app->urlManager->createUrl(['/files/default/download', 'filePath' => $entity->subdir . $entity->fileName]);
        return str_replace('%2F', urldecode('%2F'), $ret);
    }

    /**
     * Get files redirect uri
     * @param $filePath
     * @return bool|string
     */
    public function getFileRedirectUri($filePath) {
        return \yii::getAlias("@commonFilesUri/$filePath");
    }

    /**
     * Get image uri
     * @param string $entityName
     * @param string $imageName
     * @return string
     * @throws InvalidConfigException
     */
    public function getFileUri(string $entityName, string $imageName) {
        $entity = $this->getEntityInstance($entityName);
        $entity->fileName = $imageName;
        $ret = \Yii::$app->urlManager->createUrl(['/files/default/download', 'filePath' => $entity->subdir . DIRECTORY_SEPARATOR . $entity->fileName]);
        return str_replace('%2F', urldecode('%2F'), $ret);
    }

    /**
     * Create entity
     * @param string $entityName
     * @param string $fileName
     * @return BaseFile|object
     * @throws InvalidConfigException
     */
    public function createEntity($entityName, $fileName) {
        if (!array_key_exists($entityName, $this->entities)) {
            throw new InvalidConfigException('The requested essence is not defined.');
        }

        $objectData = $this->entities[$entityName];
        if (!is_array($objectData)) {
            throw new InvalidConfigException('The configuration of this entity should be an array.');
        }

        $objectData['entityName'] = $entityName;
        $objectData['fileName'] = $fileName;

        return \Yii::createObject($objectData);
    }

    /**
     * Get entity instance
     * @param string $entityName
     * @return BaseFile|object
     * @throws InvalidConfigException
     */
    public function getEntityInstance(string $entityName) {
        if (array_key_exists($entityName, $this->_entityInstances)) {
            return $this->_entityInstances[$entityName];
        }

        return $this->createEntity($entityName, '');
    }
}
