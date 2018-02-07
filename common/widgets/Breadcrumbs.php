<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 07.02.2018
 * Time: 13:59
 */

namespace common\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class Breadcrumbs extends \yii\widgets\Breadcrumbs
{
    /**
     * @return string|null
     * @throws \yii\base\InvalidConfigException
     */
    public function run() {
        $app = Yii::$app;

        $controller = $app->controller;
        $module = $controller->module;
        $action = $app->requestedAction;
        $addBc = $this->links;
        
        /** @var \yii\web\View $this */
        $beginingBc = [];
        if ($module !== $app) {
            $bc['label'] = Yii::t('app.common', $module->id);
            $route = '/' . $module->id . '/' . $module->defaultRoute;
            if (strpos($module->defaultRoute, '/') === false)
                $route .= '/' . $module->createController($module->defaultRoute)[0]->defaultAction;
            $bc['url'] = [Url::toRoute($route)];
            $beginingBc[] = $bc;
        }
        
        if(strpos($module->defaultRoute, $controller->id) !== 0) {
            $bc['label'] = Yii::t('app.common', $controller->id);
            $bc['url'] = [Url::toRoute("/{$module->id}/{$controller->id}/{$controller->defaultAction}")];
            $beginingBc[] = $bc;
        }
        
        if($action->id !== $controller->defaultAction) {
            $url = Url::toRoute("/{$module->id}/{$controller->id}/{$action->id}");
            if ($beginingBc[count($beginingBc) - 1]['url'][0] !== $url) {
                $bc['label'] = Yii::t('app.common', $action->id);
                $bc['url'] = [$url];
                $beginingBc[] = $bc;
            }
        }
        
        foreach ($beginingBc as &$tmpBc) {
            if (!empty($addBc[0]) && is_string($addBc[0])) {
                $addBc[0] = ['label' => $addBc[0]];
            }
            
            if (
                empty($addBc[0])
                || !is_array($addBc[0])
                || (
                    empty($addBc[0]['url'])
                    && $tmpBc['label'] !== ((array) $addBc)[0]['label']
                )
            ){
                break;
            } elseif (isset($addBc[0]['url'])) {
                $addBc[0]['url'] = (array) $addBc[0]['url'];
                if (strpos($addBc[0]['url'][0], '/') !== 0) {
                    $addBc[0]['url'][0] = "/{$module->id}/{$controller->id}/{$addBc[0]['url'][0]}";
                }
                
                if ($tmpBc['url'][0] !== ((array)$addBc[0]['url'])[0]) {
                    break;
                }
            }
            
            $tmpBc['label'] = ((array) $addBc)[0]['label'];
            array_shift($addBc);
        }
        
        $bc = ArrayHelper::merge($beginingBc, $addBc);
        if (count($bc) > 1 && is_array($bc[count($bc) - 1]))
            unset($bc[count($bc) - 1]['url']);
        elseif (!yii::$app->response->getIsOk())
            $bc[] = ['label' => yii::$app->response->getStatusCode() . ' ' . yii::$app->response->statusText];
        
        $this->links = $bc;

        return parent::run();
    }
}