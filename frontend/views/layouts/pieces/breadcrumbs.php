<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 13.01.2018
 * Time: 14:31
 */

/** @var \yii\web\View $this */
$beginingBc = [];
if (yii::$app->controller->module !== yii::$app) {
    $module = yii::$app->controller->module;
    $beginingBc[] = [
        'label' => \Yii::t('app', $module->id),
        'url' => ['/' . $module->id],
        ''
    ];
} elseif(\Yii::$app->controller->id !== 'default') {
    $controllerName = \Yii::$app->controller->id;
    $beginingBc[] = [
        'label' => \Yii::t('app', $controllerName),
        'url' => ['/' . $controllerName],
        ''
    ];
}

$breadcrumbs = $this->params['breadcrumbs'];
?>

<div id="breadcrumb-container">
    <div class="container">
        <?= \yii\widgets\Breadcrumbs::widget([
            'homeLink' => [
                'label' => 'Главная',
                'url' =>  [\yii\helpers\Url::home()],
            ],
            'links' => \yii\helpers\ArrayHelper::merge($beginingBc, $breadcrumbs),
        ]); ?>
    </div>
</div>
