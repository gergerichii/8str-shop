<?php

use common\dependencies\DependencyFactory;
use common\dependencies\RubricsDependency;
use common\modules\catalog\models\Product;
use common\modules\catalog\models\ProductBrand;
use common\modules\catalog\models\ProductRubric;
use kartik\grid\GridView;
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
    
    .grid-view td {
         white-space: normal;
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
    
    // let body = $('body');
//    body.delegate("#product-quick-search-input", 'typeahead:open', correctSearchMenu);
    
    
    // body.on('shown.bs.dropdown', function(e) {
        // let found = e.relatedTarget.id.match(/^(?<name>.*?)-tree-input$/);
        // if (found.length) {
        //     let input = $('#' + found.groups.name + '-tree-input');
        //     let menu = $('#' + found.groups.name + '-tree-input-menu');
        //     let head = $(input).parents('div.floatThead-wrapper');
        //     if (menu.parent()[0] !== head[0]) {
        //         $(head).append(menu);
        //     }
        //     let inputOffset = $(input).offset();
        //     $(menu).css({display: 'block', width: '500px'})
        //         .offset(function() {
        //             let pos = {};
        //             pos.left = inputOffset.left;
        //             pos.top = inputOffset.top + $(input).outerHeight() + 6;
        //             return pos;
        //         });
        // }
    // });
    // body.on('hidden.bs.dropdown', function(e) {
        // let found = e.relatedTarget.id.match(/^(?<name>.*?)-tree-input$/);
        // if (found.length) {
        //     let menu = $('#' + found.groups.name + '-tree-input-menu');
        //     $(menu).css({display: 'none'});
        // }
    // });
    
    // $('#main_rubric_tree_filter').parent().parent().delegate('');
</script>
<?php \common\helpers\ViewHelper::endRegisterScript(); ?>

<?php

$rubricsQuery = ProductRubric::find()
    ->addOrderBy('tree, left_key')
    ->setCacheDependency(DependencyFactory::getDependency('rubrics'))
    ->withProductsCountsInName();
return [
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
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
//    [
//        'class' => 'kartik\grid\RadioColumn',
//        'width' => '36px',
//        'headerOptions' => ['class' => 'kartik-sheet-style'],
//    ],
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '28px',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id',
        'width' => '83px',
        'vAlign' => 'middle',
        'hAlign' => 'center',
    ],
    [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '50px',
        'value' => function () {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model) {
            return Yii::$app->controller->renderPartial('_expand-row-details', ['model' => $model]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'expandOneOnly' => true,
    ],
    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'name',
        'width' => '500px',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'editableOptions' => function ($model, $key, $index) {
            return [
                'header' => 'Название',
                'size' => 'md',
                'formOptions'=>['action' => ['edit-grid-product']],
                'afterInput' => function($form, $widget) use ($model, $key, $index) {
                    echo $form->field($model, "[{$index}]model");
                },
            ];
        },
            
        'refreshGrid' => true,
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
//                'menu' => new \yii\web\JsExpression("createSearchMenu()"),
                'minLength' => 2,
            ],
            'container' => ['class' => 'main-search-field'],
        ],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'model',
        'width' => '200px',
        'vAlign' => 'middle',
        'hAlign' => 'center',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'brandName',
        'width' => '180px',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'value' => function ($model) {
            return "[{$model->brand_id}] {$model->brandName}";
        },
        'filterType' => '\kartik\widgets\Select2',
        'filterWidgetOptions' => [
            'data' => ArrayHelper::merge(
                ['' => 'Любой'],
                ArrayHelper::map(
                    ProductBrand::find()->select(['name', 'id'])->orderBy('name')->asArray()->indexBy('id')->all(),
                    'name', 'name'
                )
            ),
        ],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
//        'class' => '\kartik\grid\EditableColumn',
        'attribute' => 'main_rubric_id',
        'width' => '200px',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'value' => function($model) {
            $productCounts = ProductRubric::getProductsCounts();
            $count = isset($productCounts[$model->mainRubric->id]) ? $productCounts[$model->mainRubric->id] : '-';
            return "[{$model->main_rubric_id}] {$model->rubricName} ({$count})";
        },

//        'editableOptions' => function ($model, $key, $index) use ($rubricsQuery) {
//            return [
//                'header' => 'Основная рубрика',
//                'size' => 'md',
//                'formOptions'=>['action' => ['edit-grid-product']],
//                'inputType' => 'widget',
//                'widgetClass' => '\kartik\tree\TreeViewInput',
//                'options' => [
//                    'query' => $rubricsQuery,
//                    'multiple' => false,
//                    'dropdownConfig' => [
//                        'dropdown' => [
//                            'style' => [
//                                'width' => '430px',
//                            ],
//                        ],
//                    ],
//                ]
//            ];
//        },
//
//        'refreshGrid' => true,
        
        'filterType' => '\kartik\tree\TreeViewInput',
        'filterWidgetOptions' => [
            'query' => $rubricsQuery,
            'headingOptions' => ['label' => 'Каталог'],
            'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
            'fontAwesome' => true,
            'asDropdown' => true,
            'multiple' => false,
            'showToolbar' => false,
            'options' => [
                'disabled' => false,
            ],
            'dropdownConfig' => [
                'dropdown' => [
                    'style' => [
                        'width' => '430px',
                    ],
                ],
            ],
        ],
    ],
    [
        'attribute' => 'status',
        'width' => '140px',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'value' => function ($model, $key, $index, $widget) {
            $statuses = [
                Product::STATUS['ACTIVE'] => 'Активный',
                Product::STATUS['DELETED'] => 'Удален',
                Product::STATUS['HIDDEN'] => 'Скрыт',
            ];
            return $statuses[$model->status];
        },
        'filterType' => '\kartik\widgets\Select2',
        'filterWidgetOptions' => [
            'data' => [
                '' => 'Все',
                Product::STATUS['ACTIVE'] => 'Активные',
                Product::STATUS['DELETED'] => 'Удаленные',
                Product::STATUS['HIDDEN'] => 'Скрытые',
            ],
            'hideSearch' => true,
        ],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'count',
        'width' => '100px',
        'vAlign' => 'middle',
        'hAlign' => 'center',
    ],
    [
        'attribute' => 'show_on_home',
        'class' => 'kartik\grid\BooleanColumn',
        'width' => '100px',
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
//    [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'files',
//    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'delivery_time',
        'width' => '80px',
        'vAlign' => 'middle',
        'hAlign' => 'center',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'delivery_days',
        'width' => '113px',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'filterType' => '\kartik\widgets\Select2',
        'filterWidgetOptions' => [
            'data' => ArrayHelper::merge(
                ArrayHelper::map(
                    Product::find()->select('delivery_days')
                        ->distinct()
                        ->groupBy('delivery_days')
                        ->orderBy('delivery_days')
                        ->asArray()
                        ->all(),
                    'delivery_days', 'delivery_days'
                ), ['' => 'Любой']
            ),
        ],
    ],
//    [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'created_at',
//    ],
//    [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'modified_at',
//    ],
//    [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'creator_id',
//    ],
//    [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'modifier_id',
//    ],
//    [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'product_type_id',
//    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'old_id',
        'width' => '83px',
        'vAlign' => 'middle',
        'hAlign' => 'center',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'width' => '83px',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'attribute' => 'old_rubric_id',
    ],
//    [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'vendor_code',
//    ],
//    [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'barcode',
//    ],
//    [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'warranty',
//    ],
];