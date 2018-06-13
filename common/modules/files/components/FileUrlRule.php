<?php

namespace common\modules\files\components;

use yii\base\BaseObject;
use yii\web\Request;
use yii\web\UrlManager;
use yii\web\UrlRuleInterface;

/**
 * Class FileUrlRule
 */
class FileUrlRule extends BaseObject implements UrlRuleInterface
{
    /**
     * @var string;
     */
    public $filesManagerModuleId = 'files';
    
    /**
     * Parses the given request and returns the corresponding route and parameters.
     * @param UrlManager $manager the URL manager
     * @param Request $request the request component
     * @return array|bool the parsing result. The route and the parameters are returned as an array.
     * If false, it means this rule cannot be used to parse this path info.
     * @throws \yii\base\InvalidConfigException
     */
    public function parseRequest($manager, $request) {
        $pathInfo = $request->getPathInfo();
        /** @var \common\modules\files\FilesModule $filesUrlManager */
        $filesUrlManager = \Yii::$app->getModule($this->filesManagerModuleId);
        $pattern = "%^{$filesUrlManager->id}/(?:(?P<_a>download|upload|.{0})/)?(?:(?P<protected>protected|.{0})/)?(?P<filePath>[\w\-\.,/_-]*)%iu";
        if (preg_match($pattern, $pathInfo, $matches)) {
            /** @var \yii\web\Controller $defController */
            $defController = $filesUrlManager->createController($filesUrlManager->defaultRoute)[0];
            $action = (empty($matches['_a'])) ? $defController->defaultAction : $matches['_a'];
            $params = [
                'isProtected' => (boolean)$matches['protected'],
                'entityType' => 'defaults',
                'fileName' => $matches['filePath'],
            ];
            
            foreach (array_keys($filesUrlManager->entities) as $entityType) {
                if (0 === strpos($matches['filePath'], $entityType)) {
                    $params['entityType'] = $entityType;
                    $params['fileName'] = substr($matches['filePath'], strlen($entityType) + 1);
                    break;
                }
            }
            
            return ["/{$filesUrlManager->defaultUri}/{$action}", $params];
        }

        return false; // this rule does not apply
    }

    /**
     * Creates a URL according to the given route and parameters.
     * @param UrlManager $manager the URL manager
     * @param string $route the route. It should not have slashes at the beginning or the end.
     * @param array $params the parameters
     * @return string|bool the created URL, or false if this rule cannot be used for creating this URL.
     */
    public function createUrl($manager, $route, $params) {
        /** @var \common\modules\files\FilesModule $filesUrlManager */
        $filesUrlManager = \Yii::$app->getModule($this->filesManagerModuleId);
        
        if (0 === strpos($route, $filesUrlManager->defaultUri) && !empty($params['fileName'])) {
            $action = (substr($route, strlen($filesUrlManager->defaultUri) + 1) === 'upload') ? 'upload/' : '';
            $protected = empty($params['isProtected']) ? '' : 'protected/';
            $entityType = empty($params['entityType']) ? 'defaults' : $params['entityType'];
            
            return "/{$filesUrlManager->id}/{$action}{$protected}{$entityType}/{$params['fileName']}";
        }

        return false;
    }
}