<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\modules\catalog\models\ProductTag;
use kartik\widgets\Select2;
use common\modules\catalog\models\ProductRubric;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $model common\modules\catalog\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php ob_start(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'brand_id')->widget('\kartik\widgets\Select2', [
                'data' => \yii\helpers\ArrayHelper::map(
                    \common\modules\catalog\models\ProductBrand::find()->select(['name', 'id'])->orderBy('name')->asArray()->indexBy('id')->all(),
                    'name', 'name'
                ),
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'main_rubric_id')->widget('\kartik\tree\TreeViewInput', [
                'query' => ProductRubric::find()
                    ->indexBy('id')
                    ->addOrderBy('tree, left_key')
                    ->withProductsCountsInName(),
                'headingOptions' => ['label' => 'Каталог'],
                'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
                'fontAwesome' => true,
                'asDropdown' => true,
                'multiple' => false,
                'showToolbar' => false,
                'options' => [
                    'disabled' => false,
                    'id' => 'edit_main_rubric',
                ],
                'dropdownConfig' => [
                    'dropdown' => [
                        'style' => [
                            'width' => '430px',
                        ],
                    ],
                ],
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'status')->widget('\kartik\widgets\Select2', [
                'data' => [
                    \common\modules\catalog\models\Product::STATUS['ACTIVE'] => 'Активный',
                    \common\modules\catalog\models\Product::STATUS['DELETED'] => 'Удален',
                    \common\modules\catalog\models\Product::STATUS['HIDDEN'] => 'Скрыт',
                ],
                'hideSearch' => true,
            ]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'count')->widget('kartik\widgets\TouchSpin', []) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'show_on_home')->widget('kartik\widgets\SwitchInput', []) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'on_list_top')->widget('kartik\widgets\SwitchInput', []) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'market_upload')->widget('kartik\widgets\SwitchInput', []) ?>
        </div>
    </div>
    

    <?=$form->field($model, 'desc')->widget(\dosamigos\ckeditor\CKEditor::class, [
        'preset' => 'custom',
        'clientOptions' => [
            'toolbarGroups' => [
                ['name' => 'document', 'groups' => ['mode', 'document', 'doctools']],
                ['name' => 'clipboard', 'groups' => ['clipboard', 'undo']],
                ['name' => 'editing', 'groups' => [ 'find', 'selection', 'spellchecker']],
                ['name' => 'forms'],
                ['name' => 'basicstyles', 'groups' => ['basicstyles', 'colors','cleanup']],
                ['name' => 'paragraph', 'groups' => [ 'list', 'indent', 'blocks', 'align', 'bidi' ]],
                ['name' => 'links'],
                ['name' => 'insert'],
                ['name' => 'styles'],
                ['name' => 'blocks'],
                ['name' => 'colors'],
                ['name' => 'tools'],
                ['name' => 'others'],
            ],
            
        ]
    ]);?>
    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'tagCollection')->widget(Select2::class, [
                'data' => ProductTag::getTagsForSelect(),
                'options' => ['placeholder' => 'Выбрать метки...', 'multiple' => true],
                'pluginOptions' => [
                    'tags' => true,
                    'tokenSeparators' => [',', ' '],
                    'maximumInputLength' => 10
                ],
            ]); ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'delivery_time')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'delivery_days')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'vendor_code')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'barcode')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'warranty')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>
    <? $tabs = []; $tabs[] = [
        'label' => 'Основные параметры',
        'content' => ob_get_clean(),
        'active' => true,
    ]; ?>
    
    <?php ob_start(); ?>
        Prices
    <?php $tabs[] = [
        'label' => 'Цены и скидки',
        'content' => ob_get_clean(),
    ]; ?>

    <?php ob_start(); ?>
        Files
    <?php $tabs[] = [
        'label' => 'Картинки и файлы',
        'content' => ob_get_clean(),
    ] ?>
    
    <?=TabsX::widget([
        'items' => $tabs,
        'position'=>TabsX::POS_ABOVE,
        'encodeLabels'=>true,
        'bordered' => true,
    ]) ?>
    
    <?php ActiveForm::end(); ?>
    
</div>
