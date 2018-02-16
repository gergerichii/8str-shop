<?php

use common\modules\catalog\models\Product;
use common\modules\catalog\models\ProductRubric;
use common\modules\catalog\models\ProductTag;
use common\modules\treeManager\TreeViewInput;
use kartik\date\DatePicker;
use kartik\number\NumberControl;
use kartik\widgets\Select2;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\MultipleInputColumn;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\catalog\models\Product */
/* @var $form yii\widgets\ActiveForm */

TreeViewInput::$autoIdPrefix = 'treeViewInput';
Select2::$autoIdPrefix = 'select2';
MultipleInput::$autoIdPrefix = 'multipleInput';
NumberControl::$autoIdPrefix = 'numberControl';

?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tagCollection')->widget(Select2::class, [
        'data' => ProductTag::getTagsForSelect(),
        'options' => ['placeholder' => 'Select a tag ...', 'multiple' => true],
        'pluginOptions' => [
            'tags' => true,
            'tokenSeparators' => [',', ' '],
            'maximumInputLength' => 10
        ],
    ]); ?>

    <?=
    $form->field($model, 'desc')->widget(\dosamigos\ckeditor\CKEditor::class, [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]);
    ?>

    <?php /*=
    $form->field($model, 'desc')->widget(TinyMce::className(), [
        'options' => ['rows' => 6],
        'language' => 'ru',
        'clientOptions' => [
            'plugins' => [
                "advlist autolink lists link charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste"
            ],
            'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
        ]
    ]);*/
    ?>

    <?=
    $form->field($model, 'main_rubric_id')->widget(TreeViewInput::class, [
        'name' => 'main_rubric_id',
        'value' => $model->main_rubric_id,
        'query' => ProductRubric::find()->addOrderBy('tree, left_key'),
        'headingOptions' => ['label' => 'List of rubrics'],
        'rootOptions' => ['label' => '<i class="fa fa-tree text-success"></i>'],
        'fontAwesome' => true,
        'asDropdown' => true,
        'multiple' => false,
        'options' => ['disabled' => false],
    ]);
    ?>

    <?=
    $form->field($model, 'listOfRubrics')->widget(TreeViewInput::class, [
        'name' => 'listOfRubrics',
        'value' => $model->listOfRubrics,
        'query' => ProductRubric::find()->addOrderBy('tree, left_key'),
        'headingOptions' => ['label' => 'List of rubrics'],
        'rootOptions' => ['label' => '<i class="fa fa-tree text-success"></i>'],
        'fontAwesome' => true,
        'asDropdown' => true,
        'multiple' => true,
        'options' => ['disabled' => false],
    ]);
    ?>

    <?=
    $form->field($model, 'actualPrices')->widget(MultipleInput::class, [
        'id' => 'pricesMultipleInput',
        'max' => 2,
        'min' => 2,
        'allowEmptyList' => false,
        'enableGuessTitle' => true,
        'addButtonPosition' => MultipleInput::POS_HEADER,
        'enableError' => true,
        'columns' => [
            [
                'name' => 'id',
                'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
            ],
            [
                'name' => 'value',
                'type' => \yii\widgets\MaskedInput::class,
                'title' => 'Price',
                'options' => [
                    'clientOptions' => [
                        'alias' => 'currency',
                        'prefix' => '',
                        'suffix' => '',
                        'groupSeparator' => '',
                        'radixPoint' => '.'
                    ]
                ],
                'enableError' => true,
            ],
            [
                'name' => 'active_from',
                'type' => DatePicker::className(),
                'title' => 'Active from',
                'options' => [
                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy',
                        'todayHighlight' => true
                    ]
                ],
                'enableError' => true,
            ]
        ]
    ]);
    ?>

    <?= $form->field($model, 'status')->widget(Select2::class, [
        'data' => array_flip(Product::STATUS),
        'options' => ['placeholder' => 'Select a status ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
