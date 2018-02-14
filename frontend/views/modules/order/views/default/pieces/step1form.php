<?php
/* @var $this yii\web\View */

/* @var $step2form \common\modules\order\forms\frontend\Step1Form */

?>

<div class="row">

    <div class="col-md-6 col-sm-6 col-xs-12">
        <h2 class="checkout-title">Новый покупатель</h2>
        <p>Как вы предпочитаете продолжить формление заказа?</p>
        <div class="xs-margin"></div>
        <div class="input-group custom-checkbox sm-margin">
            <label>
                <input name="checkoutOptions" value="guest" type="radio">
                Продолжить как гость
            </label>
        </div><!-- End .input-group -->
        <div class="input-group custom-checkbox sm-margin">
            <label>
                <input name="checkoutOptions" value="register" type="radio" checked>
                Зарегистрироваться
            </label>

        </div><!-- End .input-group -->
        <p>Зарегистрировавшись, Вы сможете делать покупки гораздо быстрее. Избранные
            товары будут оставаться в разделе "Избранное". Также, Вы сможете
            воспользоваться персональными скидками и другими программами нашего
            магазина.
        </p>
        <div class="md-margin"></div>

    </div><!-- End .col-md-6 -->

    <div class="col-md-6 col-sm-6 col-xs-12">
        <h2 class="checkout-title">Уже зарегистрированы?</h2>
        <p>Войдите под своим аккаунтом, и... Добро пожаловать к нам вновь!</p>
        <div class="xs-margin"></div>

        <div class="input-group">
            <span class="input-group-addon"><span
                    class="input-icon input-icon-email"></span><span class="input-text">Email&#42;</span></span>
            <input type="text" required class="form-control input-lg"
                   placeholder="Ваш Email">
        </div><!-- End .input-group -->
        <div class="input-group xs-margin">
            <span class="input-group-addon"><span
                    class="input-icon input-icon-password"></span><span
                    class="input-text">Пароль&#42;</span></span>
            <input type="text" required class="form-control input-lg"
                   placeholder="Ваш пароль">
        </div><!-- End .input-group -->
        <span class="help-block text-right"><a href="#">Забыли пароль?</a></span>
        <div class="input-group custom-checkbox sm-margin top-10px">
            <input type="checkbox">
            <span class="checbox-container">
                <i class="fa fa-check"></i>
            </span>
            Запомнить пароль

        </div><!-- End .input-group -->
    </div><!-- End .col-md-6 -->

</div><!-- End.row -->

<a href="#" class="btn btn-custom-2">CONTINUE</a>
