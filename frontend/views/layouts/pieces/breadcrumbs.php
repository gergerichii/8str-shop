<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 13.01.2018
 * Time: 14:31
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$app = yii::$app;
if ($app->request->url === $app->homeUrl) {
    return;
}
$controller = $app->controller;
$module = $controller->module;
$action = $app->requestedAction;
$addBc = !empty($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [];



/** @var \yii\web\View $this */
$beginingBc = [];
if ($module !== $app) {
    $bc['label'] = \Yii::t('app', $module->id);
    $bc['url'] = [Url::toRoute('/' . $module->id)];
    $beginingBc[] = $bc;
}

if($controller->id !== 'default') {
    $bc['label'] = \Yii::t('app', $controller->id);
    $bc['url'] = [Url::toRoute("/{$module->id}/{$controller->id}")];
    $beginingBc[] = $bc;
}

if($action->id !== $controller->defaultAction) {
    $url = Url::toRoute("/{$module->id}/{$controller->id}/{$action->id}");
    if ($beginingBc[count($beginingBc) - 1]['url'][0] !== $url) {
        $bc['label'] = \Yii::t('app', $action->id);
        $bc['url'] = [$url];
        $beginingBc[] = $bc;
    }
}

$bc = ArrayHelper::merge($beginingBc, $addBc);
unset($bc[count($bc) - 1]['url']);

?>

<div id="breadcrumb-container">
    <div class="container">
        <?= \yii\widgets\Breadcrumbs::widget([
            'homeLink' => [
                'label' => 'Главная',
                'url' =>  [Url::home()],
            ],
            'links' => $bc,
        ]); ?>
    </div>
</div>
