<?php

use common\modules\treeManager\TreeViewInput;
use kartik\number\NumberControl;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\MultipleInputColumn;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model common\modules\catalog\models\forms\ProductImagesForm */
/* @var $form yii\widgets\ActiveForm */

TreeViewInput::$autoIdPrefix = 'treeViewInput';
Select2::$autoIdPrefix = 'select2';
MultipleInput::$autoIdPrefix = 'multipleInput';
NumberControl::$autoIdPrefix = 'numberControl';

$amountOfDomains = count(Yii::$app->params['domains']);

?>

<div class="product-prices">
    <div class="product-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'prices')->widget(MultipleInput::class, [
            'id' => 'pricesMultipleInput',
            'allowEmptyList' => false,
            'enableGuessTitle' => true,
            'addButtonPosition' => MultipleInput::POS_HEADER,
            'enableError' => true,
            'min' => $amountOfDomains,
            'max' => $amountOfDomains,
            'columns' => [
                [
                    'name' => 'id',
                    'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                ],
                [
                    'name' => 'domain_name',
                    'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                ],
                [
                    'title' => 'Domain name',
                    'name' => 'domain_name',
                    'type' => MultipleInputColumn::TYPE_STATIC,
                ],
                [
                    'name' => 'value',
                    'type' => MaskedInput::class,
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
            ]
        ]); ?>

        <?=
        $form->field($model, 'futurePrices')->widget(MultipleInput::class, [
            'id' => 'pricesMultipleInput',
            'allowEmptyList' => false,
            'enableGuessTitle' => true,
            'addButtonPosition' => MultipleInput::POS_HEADER,
            'enableError' => true,
            'min' => $amountOfDomains,
            'max' => $amountOfDomains,
            'columns' => [
                [
                    'name' => 'id',
                    'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                ],
                [
                    'name' => 'domain_name',
                    'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                ],
                [
                    'title' => 'Domain name',
                    'name' => 'domain_name',
                    'type' => MultipleInputColumn::TYPE_STATIC,
                ],
                [
                    'name' => 'value',
                    'type' => MaskedInput::class,
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
                    'type' => DateTimePicker::class,
                    'title' => 'Active from',
                    'options' => [
                        'options' => ['placeholder' => 'Select operating time ...'],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd hh:ii:ss',
                            'todayHighlight' => true
                        ]
                    ],
                    'enableError' => true,
                ]
            ]
        ]);
        ?>

        <?php if (!Yii::$app->request->isAjax) { ?>
            <div class="form-group">
                <?= Html::submitButton('Update', ['class' => 'btn btn-primary']); ?>
            </div>
        <?php } ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>
