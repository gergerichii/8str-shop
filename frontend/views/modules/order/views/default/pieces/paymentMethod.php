<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 28.03.2018
 * Time: 19:27
 */

use common\modules\order\forms\frontend\OrderForm;

/** @var \yii\web\View $this */
/** @var \common\modules\order\forms\frontend\OrderForm $orderForm */

?>

<?php $form = kartik\form\ActiveForm::begin([
    'options' => [
        'data' => [
            'type' => 'stepForm',
            'pjax' => 'true',
        ],
    ],
]); ?>

<?=$form->field($orderForm, 'orderStep', ['inputOptions' => ['value' => 3, 'id' => false]])->hiddenInput()->label(false)?>


<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h2 class="checkout-title">Способ оплаты</h2>
        <p>Выберете способ оплаты.</p>
        <div class="xs-margin"></div>
        
        <div class="input-group">
            <span class="input-group-addon"><span class="input-icon input-icon-money"></span><span
                    class="input-text">Способ оплаты*</span></span>
            <div class="large-selectbox clearfix">
                <?= $form->field($orderForm->paymentForm, 'methodId', ['options' => ['class' => '']])
                    ->dropDownList(
                        $orderForm->paymentForm->getPaymentMethods(),
                        ['class' => 'selectbox']
                    )->label(false) ?>
            </div><!-- End .large-selectbox-->
        </div><!-- End .input-group -->
        
    </div><!-- End .col-md-6 -->
    <?php $show = ($orderForm->paymentForm->methodId === $orderForm->paymentForm::METHOD_NON_CASH) ?>
    <div class="col-md-6 col-sm-6 col-xs-12" id = "payment-requisites-container" <?=($show) ?: 'style="display:none"'?>>
        <h2 class="checkout-title">Реквезиты компании</h2>
        <p>Заполните реквезиты для выставления счета</p>
        <div class="xs-margin"></div>
        
        <?= $form->field($orderForm->paymentForm, 'requisites')->textarea([
            'placeholder' => 'Реквизиты компании для выставления счета',
            'rows' => 5,
        ])->label(false) ?>
    </div><!-- End .col-md-6 -->
</div><!-- End .row -->



<?= \yii\helpers\Html::submitButton('Продолжить', [
    'class' => 'btn btn-custom-2',
    'data-action' => 'next-step'
]) ?>

<?php kartik\form\ActiveForm::end(); ?>

<?php common\helpers\ViewHelper::startRegisterScript($this); ?>
<script>
    $(document).delegate('#paymentmethodform-methodid', 'change', function(){
        var val = $('#paymentmethodform-methodid').val();
        var paymentRequisitesContainer = $('#payment-requisites-container');
        var toggle = val === '<?=$orderForm->paymentForm::METHOD_NON_CASH?>';
        paymentRequisitesContainer.toggle(toggle);
    });
    $.venedor.selectBox();
</script>
<?php common\helpers\ViewHelper::endRegisterScript(); ?>
