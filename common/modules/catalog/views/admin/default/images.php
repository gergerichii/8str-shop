<?php

use common\modules\treeManager\TreeViewInput;
use kartik\number\NumberControl;
use kartik\widgets\Select2;
use unclead\multipleinput\MultipleInput;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\catalog\models\forms\ProductImagesForm */
/* @var $form yii\widgets\ActiveForm */

TreeViewInput::$autoIdPrefix = 'treeViewInput';
Select2::$autoIdPrefix = 'select2';
MultipleInput::$autoIdPrefix = 'multipleInput';
NumberControl::$autoIdPrefix = 'numberControl';

?>

<div class="product-images">

    <div class="product-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= \common\modules\files\widgets\FileInput::widget([
            'model' => $model,
            'attribute' => 'images',
            'entityType' => 'products/images',
            'options' => ['multiple' => true],
            'pluginOptions' => [
                'uploadUrl' => \yii\helpers\Url::to(['/catalog/default/upload-image']),
                'uploadExtraData' => [
                    'id' => $model->id
                ],
            ]
        ]); ?>

        <input id="form-token" type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>"/>

        <?php ActiveForm::end(); ?>

    </div>
</div>
