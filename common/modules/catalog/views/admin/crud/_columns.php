<?php

use yii\helpers\Url;
?>
<?php \common\helpers\ViewHelper::startRegisterScript($this); ?>
<script>
    function prepareMainSearchRequest(query, settings) {
        settings.url += '?q=' + query;
        return settings;
    }
    function transform1(suggestions) {
        return suggestions.products;
    }
    function transform2(suggestions) {
        return suggestions.brands;
    }
    function transform3(suggestions) {
        return suggestions.rubrics;
    }
    
    function correctSearchMenu() {
    
    }
</script>
<?php \common\helpers\ViewHelper::endRegisterScript(); ?>


<?php
return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'id',
    // ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'name',
        'filterType' => '\kartik\widgets\Typeahead',
        'filterWidgetOptions' => [
            'name' => 'sk',
            'id' => 'search-keywords',
            'hashVarLoadPosition' => \yii\web\View::POS_END,
            'value' => Yii::$app->request->get('sk'),
            'options' => [
                'placeholder' => 'Поиск по каталогу...',
                'dir' => 'auto',
                'tabindex' => 19,
                'class' => 'search-input',
                'autocomplete' => 'off',
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
                'menu' => new \yii\web\JsExpression("\$('#main-search-tt-menu.tt-menu')"),
                'minLength' => 2,
            ],
            'container' => ['class' => 'main-search-field'],
            'pluginEvents' => [
                'typeahead:open' => 'function(){console.log("");}',
            ],
        ],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'title',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'status',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'count',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'show_on_home',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'on_list_top',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'market_upload',
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
        'attribute' => 'brand_id',
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