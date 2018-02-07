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

foreach ($addBc as &$tmpBc) {
    if (!is_array($tmpBc)) {
        $tmpBc = [
            'label' => $tmpBc,
        ];
    }
}


/** @var \yii\web\View $this */
$beginingBc = [
//    [
//        'label' => \yii::t('app.common', 'main'),
//        'url' => url::toRoute('/'),
//    ],
];
if ($module !== $app) {
    $bc['label'] = \Yii::t('app.common', $module->id);
    $bc['url'] = Url::toRoute('/' . $module->id);
    $beginingBc[] = $bc;
}

if(strpos($module->defaultRoute, $controller->id) !== 0) {
    $bc['label'] = \Yii::t('app.common', $controller->id);
    $bc['url'] = Url::toRoute("/{$module->id}/{$controller->id}");
    $beginingBc[] = $bc;
}

if($action->id !== $controller->defaultAction) {
    $url = Url::toRoute("/{$module->id}/{$controller->id}/{$action->id}");
    if ($beginingBc[count($beginingBc) - 1]['url'][0] !== $url) {
        $bc['label'] = \Yii::t('app.common', $action->id);
        $bc['url'] = $url;
        $beginingBc[] = $bc;
    }
}

$bc = ArrayHelper::merge($beginingBc, $addBc);
if (count($bc) > 1)
    unset($bc[count($bc) - 1]['url']);
else
    $bc[] = ['label' => yii::$app->response->getStatusCode()];

?>

<div id="breadcrumb-container">
    <div class="container">
        <?= \yii\widgets\Breadcrumbs::widget([
            'homeLink' => [
                'label' => \yii::t('app.common', 'main'),
                'url' =>  Url::home(),
            ],
            'links' => $bc,
        ]); ?>
    </div>
</div>
