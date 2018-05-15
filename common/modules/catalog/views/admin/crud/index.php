<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel \common\modules\catalog\models\search\ProductSearch */

$this->title = 'Продукты';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="product-index">
    <div id="ajaxCrudDatatable">
        <?=\kartik\dynagrid\DynaGrid::widget([
            'storage' => 'db',
            'columns' => require(__DIR__.'/_columns.php'),
            'options' => [
                'id' => 'crud-datatable',
            ],
            'gridOptions' => [
                'filterModel' => $searchModel,
                'pjax'=>true,
                'striped' => true,
                'condensed' => true,
                'responsive' => true,
                'floatHeader' => true,
                'perfectScrollbar' => true,
                'hover'=>true,
                'containerOptions' => ['style' => ['height' => '510px']],
                'toolbar'=> [
                    ['content'=>
                        Html::a('<i class="glyphicon glyphicon-plus"></i>', ['Добавить продукт'],
                        ['role'=>'modal-remote','title'=> 'Создать новый продукт','class'=>'btn btn-default']).
                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                        ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Сбросить настройки таблицы']).
                        '{toggleData}'.
                        '{export}{dynagrid}{dynagridFilter}{dynagridSort}'
                    ],
                ],
                'panel' => [
//                    'type' => 'primary',
                    'heading' => '<i class="glyphicon glyphicon-list"></i> Каталог продуктов',
                    'before' => 'test panel',
                    'after'=>BulkButtonWidget::widget([
                        'buttons' => Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Удалить все',
                            ["bulk-delete"] ,
                            [
                                "class"=>"btn btn-danger btn-xs",
                                'role'=>'modal-remote-bulk',
                                'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                'data-request-method'=>'post',
                                'data-confirm-title'=>'Вы уверены?',
                                'data-confirm-message'=>'Вы точно хотите удалить выделенные товары?'
                            ]
                        ),
                    ]).
                    '<div class="clearfix"></div>',
                ]
            ],
        ])?>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
