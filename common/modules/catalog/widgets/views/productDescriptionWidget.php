<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\modules\catalog\models\Product
 * @var $tabsConfig array
 */
?>

<?php ob_start() ?>
<style>
    .adaptive-tabs .nav.nav-tabs {
        display: none;
    }
    
    .adaptive-tabs.left .panel-heading {
        background-color: #fafafa;
        border-bottom: 1px solid #dcdcdc;
    }
    
    .adaptive-tabs.tabs-left .panel-tittle {
        text-align: center;
        padding-top: 4px;
        padding-bottom: 4px;
    }
    
    .adaptive-tabs.left .panel-tittle a {
        display: inline-block;
        width: 100%;
        height: 100%;
        font: 700 16px/20px "Gudea", Arial, sans-serif;
        text-transform: uppercase;
        color: #666;
    }
    
    .adaptive-tabs.left .tab-content {
        padding: inherit;
    }
    
    .adaptive-tabs.left .tab-content .panel {
        margin-bottom: inherit;
        background-color: inherit;
        border: unset;
        border-radius: inherit;
        -webkit-box-shadow: inherit;
        box-shadow: inherit;
    }
    
    .adaptive-tabs.left .tab-content .panel.tab-pane {
        display: inherit;
        padding: inherit;
        margin-left: inherit;
        margin-right: inherit;
    }
    
    .adaptive-tabs.left .tab-content .panel.tab-pane .collapsing,
    .adaptive-tabs.left .tab-content .panel.tab-pane .collapse {
        margin-left: 30px;
        margin-right: 30px;
        padding-top: 10px;
        padding-bottom: 10px;
    }
    
    .adaptive-tabs.left .tab-content .panel.tab-pane.fade {
        opacity: inherit;
    }
</style>
<?php $css = ob_get_clean(); ?>

<?php
$tabs = [
    [
        'label' => 'Описание',
        'content' => $model->desc,
        'active' => true,
        'contentOptions' => ['class' => 'in'],
    ]
];
$tabs[] = [
    'label' => 'Технические характеристики',
    'content' => ($model->tech_desc) ? $model->tech_desc : '',
];

$config = [
    'items' => $tabs,
    'position'=>null,
    'align'=>null,
    'encodeLabels'=>true,
    'bordered' => false,
    'collapseCss' => $css,
    'pluginOptions' => [
        'addCss' => '' //нужно чтобы убрать класс по умолчанию
    ],
    'containerOptions' => [
        'class' => ['tab-container', 'left', 'product-detail-tab', 'clearfix'],
    ],
    'options' => [
        'style' => 'height: 378px'
    ]
];

$config = \yii\helpers\ArrayHelper::merge($config, $tabsConfig);
?>


<?=\common\widgets\AdaptiveTabs::widget($config) ?>

