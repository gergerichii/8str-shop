<?php

namespace common\modules\files;

use common\modules\baseModule\BaseModule;
use common\modules\files\components\FilesManager;
use yii\helpers\ArrayHelper;

/**
 * Class FilesModule for the Files
 *
 * @property FilesManager $manager
 * @property mixed $defaultUri
 */
class FilesModule extends BaseModule
{
    const DEFAULT_ID = 'files';
    const MANAGER_ID = 'manager';
    
    protected $_defaultUri;

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'common\modules\files\controllers';
    
    /**
     * FilesModule constructor.
     *
     * @param       $id
     * @param null  $parent
     * @param array $config
     */
    public function __construct($id, $parent = null, $config = []) {
        foreach($this->coreComponents() as $componentId => $component) {
            if (isset($config['components'][$componentId])) {
                $config['components'][$componentId] = ArrayHelper::merge($component, $config['components'][$componentId]);
            } else {
                $config['components'][$componentId] = $component;
            }
        }
        
        
        parent::__construct($id, $parent, $config);
    }
    
    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        
        // custom initialization code goes here
        $this->_defaultUri = "{$this->id}/{$this->defaultRoute}";
    }
    
    /**
     * @return \common\modules\files\components\FilesManager|object
     * @throws \yii\base\InvalidConfigException
     */
    public function getManager() {
        return $this->get(self::MANAGER_ID);
    }

    /**
     * Returns the default uri for this module
     * @return mixed
     */
    public function getDefaultUri() {
        return $this->_defaultUri;
    }
    
    public function coreComponents() {
        return array(
            static::MANAGER_ID => [
                'class' => FilesManager::class,
                'filesModule' => $this,
            ]
        );
    }
}
