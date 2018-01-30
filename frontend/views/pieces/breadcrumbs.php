<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 13.01.2018
 * Time: 14:31
 */

/** @var \yii\web\View $this */

$controllerName = \Yii::$app->controller->id;

$breadcrumbs = isset($this->params['breadcrumbs'])
    ? $this->params['breadcrumbs'] : [];
?>

<div id="breadcrumb-container">
    <div class="container">
        <?= \yii\widgets\Breadcrumbs::widget([
            'homeLink' => [
                'label' => 'Главная',
                'url' =>  [\yii\helpers\Url::home()],
            ],
            'links' => \yii\helpers\ArrayHelper::merge([
                [
                    'label' => \Yii::t('app', $controllerName),
                    'url' => ['/' . $controllerName],
                    ''
                ]
            ], $breadcrumbs),
        ]); ?>
    </div>
</div>
