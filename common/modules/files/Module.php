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
 *
 * @property mixed $defaultUri
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
     * @var string
     */
    public $publicPath;
    
    /**
     * @var string
     */
    public $protectedPath;

    /**
     * Entities instances
     * @var array
     */
    private $_entityInstances = [];
    
    protected $_defaultUri;

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        
        // custom initialization code goes here
        $this->_defaultUri = "{$this->id}/{$this->defaultRoute}";
    }
    
    /**
     * Returns the default uri for this module
     * @return mixed
     */
    public function getDefaultUri() {
        return $this->_defaultUri;
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
                'filesManagerModuleId' => $this->id,
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
     *
     * @param string $entityType
     * @param string $fileName
     * @param bool   $isProtected
     * @param bool   $allowDefault
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getFilePath(string $entityType, string $fileName, $isProtected = false, $allowDefault = false, $checkExists = false) {
        $entity = $this->getEntityInstance($entityType);
        $entity->fileName = $fileName;
        $entity->isProtected = $isProtected;
        return $entity->getFilePath($allowDefault, $checkExists);
    }
    
    /**
     * Get image uri
     *
     * @param string $entityType
     * @param string $fileName
     * @param bool   $allowDefault
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getFileUri(string $entityType, string $fileName, $allowDefault = false) {
        $entity = $this->getEntityInstance($entityType);
        $entity->fileName = $fileName;
        return $entity->getUri(false, $allowDefault);
    }
    
    /**
     * Create entity
     *
     * @param string $entityType
     * @param string $fileName
     * @param bool   $isProtected
     *
     * @return BaseFile|object
     * @throws \yii\base\InvalidConfigException
     */
    public function createEntity($entityType, $fileName = null, $isProtected = false) {
        if (is_null($fileName)) {
            $path = \Yii::getAlias($entityType);
            $types = implode('|', array_keys($this->entities));
            $rootPaths = \Yii::getAlias($this->publicPath) . '|' . \Yii::getAlias($this->protectedPath);
            if (preg_match("%(?P<rootPath>{$rootPaths})/(?P<type>{$types})/(?P<fileName>.+)%", $path, $matches)) {
                if ($matches['rootPath'] === $this->protectedPath) {
                    $isProtected = true;
                }
                $entityType = $matches['type'];
                $fileName = $matches['fileName'];
            }
        }
        
        if (!array_key_exists($entityType, $this->entities)) {
            throw new InvalidConfigException('The requested essence is not defined.');
        }

        $objectData = $this->entities[$entityType];
        if (!is_array($objectData)) {
            throw new InvalidConfigException('The configuration of this entity should be an array.');
        }

        $objectData['entityType'] = $entityType;
        $objectData['fileName'] = $fileName;
        $objectData['filesManager'] = $this;
        $objectData['isProtected'] = $isProtected;

        return \Yii::createObject($objectData);
    }

    /**
     * Get entity instance
     * @param string $entityType
     * @return BaseFile|object
     * @throws InvalidConfigException
     */
    public function getEntityInstance(string $entityType) {
        if (array_key_exists($entityType, $this->_entityInstances)) {
            return $this->_entityInstances[$entityType];
        }

        return $this->createEntity($entityType, '');
    }
}
