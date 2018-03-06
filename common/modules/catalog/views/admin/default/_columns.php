<?php

use yii\helpers\Html;
use yii\helpers\Url;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'id',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'name',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'brandName',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'rubricName',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'template' => '{images} {prices} {view} {update} {delete}',
        'buttons' => [
            'images' => function ($url, $model, $key) {
                $icon = Html::tag('span', '', ['class' => 'glyphicon glyphicon-picture']);
                return Html::a($icon, ['images', 'id' => $model->id], [
                    'role' => 'modal-remote',
                    'title' => 'Images',
                    'data-toggle' => 'tooltip'
                ]);
            },
            'prices' => function ($url, $model, $key) {
                $icon = Html::tag('span', '', ['class' => 'glyphicon glyphicon-piggy-bank']);
                return Html::a($icon, ['prices', 'id' => $model->id], [
                    'role' => 'modal-remote',
                    'title' => 'Prices',
                    'data-toggle' => 'tooltip'
                ]);
            },
        ],
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'],
    ],
];
