<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\modules\order\models\OrderFieldType;
use yii\helpers\ArrayHelper;
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'name')->textInput() ?>
    
        <?= $form->field($model, 'order')->textInput() ?>
    
        <?= $form->field($model, 'widget')->textInput(['placeholder' => 'common\modules\paymaster\widgets\PaymentForm']) ?>
    
        <p><?=yii::t('order', 'Widget call automacly'); ?>:</p>
        
        <pre>widgetPath::widget([
    'autoSend' => true,
    'orderModel' => $model,
    'description' => 1,
])</pre>
        <p><?=yii::t('order', 'Example')?>: <a href="https://github.com/shop/yii2-paymaster">shop/yii2-paymaster</a></p>
        
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('order', 'Create') : Yii::t('order', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
