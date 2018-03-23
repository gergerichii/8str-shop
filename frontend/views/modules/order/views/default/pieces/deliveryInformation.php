<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 22.03.2018
 * Time: 17:03
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

<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h2 class="checkout-title">способ доставки</h2>
        <p>Выберете способ доставки.</p>
        <div class="xs-margin"></div>
        
        <div class="input-group">
            <span class="input-group-addon"><span
                class="input-icon input-icon-country"></span><span class="input-text">Доставка*</span></span>
            <div class="large-selectbox clearfix">
                <select id="delivery-item-select" name="delivery-item" class="selectbox">
                    <option value="mkad">Доставка в пределах МКАД (350р)</option>
                    <option value="over_mkad">Доставка за пределы МКАД (600р)</option>
                    <option value="tk">Доставка до терменала транспортной компании (350р)</option>
                    <option value="self">Самовывоз из Шоурума на Комсомольской площади</option>
                </select>
            </div><!-- End .large-selectbox-->
        </div><!-- End .input-group -->
        
        <div class="xs-margin"></div>
        <div class="address-container">
            <h2 class="checkout-title">Адрес доставки</h2>
            <p>Выбирете или укажите новый адрес.</p>
            <div class="xs-margin"></div>
    
            <div class="input-group">
            <span class="input-group-addon"><span
                    class="input-icon input-icon-country"></span><span class="input-text">Доставка*</span></span>
                <div class="large-selectbox clearfix">
                    <select id="address-item" name="address-item" class="selectbox">
                        <option value="0">--Указать новый адрес--</option>
                    </select>
                </div><!-- End .large-selectbox-->
            </div><!-- End .input-group -->
        </div><!-- End .address-container -->
    </div><!-- End .col-md-6 -->

    <div class="col-md-6 col-sm-6 col-xs-12 address-container">
        <h2 class="checkout-title">Адрес подробно</h2>
        <p>Заполните или исправьте адрес доставки</p>
        <div class="xs-margin"></div>
        
    </div><!-- End .col-md-6 -->

</div><!-- End .row -->
<?= \yii\helpers\Html::submitButton('Продолжить', [
    'class' => 'btn btn-custom-2',
    'data-action' => 'next-step'
]) ?>

<?php kartik\form\ActiveForm::end(); ?>

<?php common\helpers\ViewHelper::startRegisterScript($this); ?>
<script>
    $(function (){
        $(document).delegate('#delivery-item-select', 'change', function (){
            var val = $(this).val();
            switch(val) {
                case:
            }
        });
    });
</script>
<?php common\helpers\ViewHelper::endRegisterScript(); ?>
