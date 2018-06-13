<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 09.06.2018
 * Time: 12:35
 */

namespace common\modules\files\components;
use common\modules\files\models\BaseFile;
use yii\base\Component;
use yii\base\InvalidConfigException;

class FilesManager extends Component {
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
     * @var \common\modules\files\FilesModule
     */
    public $filesModule;

    /**
     * Entities instances
     * @var array
     */
    private $_entityInstances = [];
    
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init() {
        parent::init();
        if (empty($this->filesModule)) {
            throw new InvalidConfigException('Files module is not set!');
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
     * @param bool   $checkExists
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getFilePath(string $entityType, string $fileName = null, $isProtected = false, $allowDefault = false, $checkExists = false) {
        $entity = $this->getEntityInstance($entityType);
        if ($fileName !== null)
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
        /** Если указан только тип, то в нем долен быть указан полный путь к файлу, из которого мы вытащим тип */
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
        !empty($fileName) and $objectData['fileName'] = $fileName;
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