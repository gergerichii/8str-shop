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
     * Parses the given request and returns the corresponding route and parameters.
     * @param UrlManager $manager the URL manager
     * @param Request $request the request component
     * @return array|bool the parsing result. The route and the parameters are returned as an array.
     * If false, it means this rule cannot be used to parse this path info.
     * @throws \yii\base\InvalidConfigException
     */
    public function parseRequest($manager, $request) {
        $pathInfo = $request->getPathInfo();
        if (preg_match('%^file\/((\w+)(\/\w+)+)\/([a-zA-ZА-Яа-я.()_ \-0-9]+\.(png|jpg|jpeg|gif))$%iu', $pathInfo, $matches)) {
            $params = [
                'entityName' => $matches[1],
                'fileName' => $matches[4]
            ];

            return ['files/default/download', $params];
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
        if ($route === 'files/default/download' && isset($params['entityName']) && isset($params['fileName'])) {
            return 'file/' . $params['entityName'] . '/' . $params['fileName'];
        }

        return false;
    }
}