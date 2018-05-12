<?php

use common\modules\catalog\models\ProductBrand;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
?>

<?php \common\helpers\ViewHelper::startRegisterCss($this) ?>
<style>
    .tt-menu,
    .tt-menu.tt-empty,
    .tt-menu.tt-open.tt-empty {
        z-index: 809;
        top: auto;
        left: auto;
        width: 800px;
        display: none;
    }
    
    .tt-menu.tt-open {
        display: block;
    }
</style>
<?php \common\helpers\ViewHelper::endRegisterCss() ?>

<?php \common\helpers\ViewHelper::startRegisterScript($this, \yii\web\View::POS_END); ?>
<script>
    function prepareMainSearchRequest(query, settings) {
        settings.url += '?q=' + query;
        return settings;
    }
    function transform1(suggestions) {
        return suggestions.products;
    }
    function correctSearchMenu() {
        var input = $('#product-quick-search-input');
        if (input.length !== 0) {
            var inputPos = $(input).offset();
            var panel = $('#search-dropdown-menu');
            
            $(panel).offset(function() {
                var pos = {};
                pos.left = inputPos.left;
                pos.top = inputPos.top + $(input).outerHeight()+6;
                return pos;
            });
            // var table = $(input).parents('table')[0];
            // var width = $(table).outerWidth() - (inputPos.left - $(table).offset().left) * 2;
            // $(panel).outerWidth(width);
        }
    }
    
    function createSearchMenu() {
        var panel = $('#search-dropdown-menu');
        if (panel.length === 0) {
            panel = $('<div id="search-dropdown-menu" class="tt-menu"></div>');
            $('.kv-grid-wrapper').prepend(panel);
        }
        return panel;
    }
    
    $('body').delegate("#product-quick-search-input", 'typeahead:open', correctSearchMenu);
</script>
<?php \common\helpers\ViewHelper::endRegisterScript(); ?>

<?php
return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
//    [
//        'class' => 'kartik\grid\SerialColumn',
//        'width' => '30px',
//    ],
     [
     'class'=>'\kartik\grid\DataColumn',
     'attribute'=>'id',
     ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'name',
        'filterType' => '\kartik\widgets\Typeahead',
        'filterInputOptions' => [
            'class' => 'search-input',
        ],
        'filterWidgetOptions' => [
            'name' => 'sk',
            'id' => 'search-keywords',
            'hashVarLoadPosition' => \yii\web\View::POS_END,
            'value' => Yii::$app->request->get('sk'),
            'options' => [
                'placeholder' => 'Поиск по каталогу...',
                'dir' => 'auto',
                'tabindex' => 19,
                'autocomplete' => 'off',
                'id' => 'product-quick-search-input'
            ],
            'dataset' => [
                [
                    'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('name')",
                    'queryTokenizer' => "Bloodhound.tokenizers.obj.whitespace",
                    'display' => 'name',
                    'limit' => 12,
                    'remote' => [
                        'url' => \yii\helpers\Url::toRoute('/search/default/index'),
                        'prepare' => new \yii\web\JsExpression('prepareMainSearchRequest'),
                        'transform' => new \yii\web\JsExpression('transform1'),
                    ],
                ],
            ],
            'pluginOptions' => [
                'highlight' => true,
                'hint' => true,
                'menu' => new \yii\web\JsExpression("createSearchMenu()"),
                'minLength' => 2,
            ],
            'container' => ['class' => 'main-search-field'],
        ],
    ],
    [
        'attribute' => 'status',
        'value' => function ($model, $key, $index, $widget) {
            $statuses = [
                \common\modules\catalog\models\Product::STATUS['ACTIVE'] => 'Активный',
                \common\modules\catalog\models\Product::STATUS['DELETED'] => 'Удален',
                \common\modules\catalog\models\Product::STATUS['HIDDEN'] => 'Скрыт',
            ];
            return $statuses[$model->status];
        },
        'filterType' => '\kartik\widgets\Select2',
        'filterWidgetOptions' => [
            'data' => [
                '' => 'Все',
                \common\modules\catalog\models\Product::STATUS['ACTIVE'] => 'Активные',
                \common\modules\catalog\models\Product::STATUS['DELETED'] => 'Удаленные',
                \common\modules\catalog\models\Product::STATUS['HIDDEN'] => 'Скрытые',
            ],
            'hideSearch' => true,
        ],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'count',
    ],
    [
        'attribute' => 'show_on_home',
        'class' => 'kartik\grid\BooleanColumn',
        'trueLabel' => 'Да',
        'falseLabel' => 'Нет',
        'filterType' => '\kartik\widgets\Select2',
        'filterWidgetOptions' => [
            'data' => [
                '' => 'Все',
                true => 'Да',
                false => 'Нет',
            ],
            'hideSearch' => true,
        ],
    ],
    [
        'attribute' => 'on_list_top',
        'class' => 'kartik\grid\BooleanColumn',
        'trueLabel' => 'Да',
        'falseLabel' => 'Нет',
        'filterType' => '\kartik\widgets\Select2',
        'filterWidgetOptions' => [
            'data' => [
                '' => 'Все',
                true => 'Да',
                false => 'Нет',
            ],
            'hideSearch' => true,
        ],
    ],
    [
        'attribute' => 'market_upload',
        'class' => 'kartik\grid\BooleanColumn',
        'trueLabel' => 'Да',
        'falseLabel' => 'Нет',
        'filterType' => '\kartik\widgets\Select2',
        'filterWidgetOptions' => [
            'data' => [
                '' => 'Все',
                true => 'Да',
                false => 'Нет',
            ],
            'hideSearch' => true,
        ],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'files',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'delivery_time',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'created_at',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'modified_at',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'creator_id',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'modifier_id',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'product_type_id',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'brandName',
        'value' => function ($model) {
            return "{$model->brandName} [{$model->brand_id}]";
        },
        'filterType' => '\kartik\widgets\Select2',
        'filterWidgetOptions' => [
            'data' => ArrayHelper::merge(
                ['' => 'Любой'],
                ArrayHelper::map(
                    ProductBrand::find()->select(['name', 'id'])->asArray()->indexBy('id')->all(),
                    'name', 'name'
                )
            ),
        ],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'main_rubric_id',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'old_id',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'old_rubric_id',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'model',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'vendor_code',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'barcode',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'warranty',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'delivery_days',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => FALSE, 'data-method' => FALSE,// for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ],
    ],
];