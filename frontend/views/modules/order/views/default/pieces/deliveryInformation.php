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

<?=$form->field($orderForm, 'orderStep', ['inputOptions' => ['value' => 2, 'id' => false]])->hiddenInput()->label(false)?>

<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h2 class="checkout-title">способ доставки</h2>
        <p>Выберете способ доставки.</p>
        <div class="xs-margin"></div>
        
        <div class="input-group">
            <span class="input-group-addon"><span
                class="input-icon input-icon-country"></span><span class="input-text">Доставка*</span></span>
            <div class="large-selectbox clearfix">
                <select id="delivery-method-select" name="OrderForm[deliveryMethod]" class="selectbox">
                    <!-- TODO: переделать в цикл -->
                    <option value="mkad" <?=$orderForm->deliveryMethod !== 'mkad' ?: 'selected';?>>Доставка в пределах МКАД (350р)</option>
                    <option value="over_mkad" <?=$orderForm->deliveryMethod !== 'over_mkad' ?: 'selected';?>>Доставказа пределы МКАД (600р)</option>
                    <option value="tk" <?=$orderForm->deliveryMethod !== 'tk' ?: 'selected';?>>Доставка до терменала транспортной компании (350р)</option>
                    <option value="self" <?=$orderForm->deliveryMethod !== 'self' ?: 'selected';?>>Самовывоз из Шоурума на Комсомольской площади</option>
                </select>
            </div><!-- End .large-selectbox-->
        </div><!-- End .input-group -->
        
        <div class="xs-margin"></div>
        <?php $hide = ($orderForm->deliveryMethod !== 'self') ? 'style="display:none"' : ''; ?>
        <div class="self-address-container" <?=$hide?>>
            <h2 class="checkout-title">Адрес самовывоза</h2>
            <p>г. Москва. Комсомольская площадь, дом 6, оф. 526. <a href="<?=\yii\helpers\Url::toRoute('/static/contacts')?>">Схема проезда</a></p>
            <div class="xs-margin"></div>
        </div><!-- End .address-container -->
        <?php $hide = ($orderForm->deliveryMethod === 'self') ? 'style="display:none"' : ''; ?>
        <div class="address-container" <?=$hide?>>
            <h2 class="checkout-title">Адрес доставки</h2>
            <p>Выбирете адрес из выпадающуего списка или укажите новый адрес.</p>
            <div class="xs-margin"></div>
    
            <div class="input-group">
            <span class="input-group-addon"><span
                    class="input-icon input-icon-country"></span><span class="input-text">Доставка*</span></span>
                <div class="large-selectbox clearfix">
                    <select id="delivery-address-select" name="OrderForm[deliveryAddressId]" class="selectbox">
                        <option value=0>-- Указать новый адрес --</option>
                        <?php foreach($orderForm->userAddresses as $i => $address): ?>
                            <?php if ((int)$address->id === 0) continue; ?>
                            <?php $checked = $i === $orderForm->deliveryAddressId ?>
                            <option value=<?=$i?> <?=($checked) ? 'selected' : ''?>><?=$address->address?></option>
                            <?php $checked = false ?>
                        <?php endforeach; ?>
                    </select>
                </div><!-- End .large-selectbox-->
            </div><!-- End .input-group -->
        </div><!-- End .address-container -->
    </div><!-- End .col-md-6 -->
    
    <div class="col-md-6 col-sm-6 col-xs-12 address-container" <?=$hide?>>
        <h2 class="checkout-title">Адрес подробно</h2>
        <p>Заполните или исправьте адрес доставки</p>
        <div class="xs-margin"></div>

        <?php $formFields = [
            'address' => 'icon:input-icon-address',
            'city' => 'icon:input-icon-city',
            'region' => 'icon:input-icon-region',
        ] ?>
        <?php $subform = null ?>
        <?php foreach($orderForm->userAddresses as $i => $model): ?>
            <?php $disabled = $orderForm->deliveryMethod === 'self' || $orderForm->deliveryAddressId !== (int)$i ?>
            <span id="address<?= $i ?>" <?= $disabled ? 'style="display: none;"' : '' ?> class="delivery-address-form-container">
                <?php $model = [$i => $model]; ?>
                <?= $this->render('signupFormGenerate', compact('model', 'form', 'formFields', 'subform', 'disabled')) ?>
            </span>
        <?php endforeach; ?>
    </div><!-- End .col-md-6 -->

</div><!-- End .row -->
<?= \yii\helpers\Html::submitButton('Продолжить', [
    'class' => 'btn btn-custom-2',
    'data-action' => 'next-step'
]) ?>

<?php kartik\form\ActiveForm::end(); ?>

<?php common\helpers\ViewHelper::startRegisterScript($this); ?>
<script>
    if (visibleAddressContainer === undefined) {
        var visibleAddressContainer = $('#address' + $('#delivery-address-select').val());
        
        toggleDeliveryAddresses = function() {
            var val = $('#delivery-method-select').val();
            var addressContainer = $('.address-container');
            var selfAddressContainer = $('.self-address-container');
            switch(val) {
                case 'self':
                    addressContainer.toggle(false);
                    $(addressContainer).find('input').prop( "disabled", true);
                    selfAddressContainer.toggle(true);
                    break;
                default:
                    addressContainer.toggle(true);
                    $(visibleAddressContainer).find('input').prop( "disabled", false);
                    selfAddressContainer.toggle(false);
                    toggleDeliveryAddressItems();
                    break;
            }
        };
        
        toggleDeliveryAddressItems = function() {
            var val = $('#delivery-address-select').val();
            var addressContainers = $('.delivery-address-form-container');
            addressContainers.toggle(false);
            $(addressContainers).find('input').prop( "disabled", true);
    
            var addressContainer = $('#address' + val);
            addressContainer.toggle(true);
            $(addressContainer).find('input').prop( "disabled", false);
            $(addressContainer).find('.checkbox.disabled').removeClass( "disabled" );
        };
        
        $(document).delegate('#delivery-method-select', 'change', toggleDeliveryAddresses);
        $(document).delegate('#delivery-address-select', 'change', toggleDeliveryAddressItems);
    }
    $.venedor.selectBox();
</script>
<?php common\helpers\ViewHelper::endRegisterScript(); ?>

