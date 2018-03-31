<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 31.03.2018
 * Time: 15:16
 */
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="checkout-title">Благодарим за Ваш заказ #<?=$orderId + 32000?>!</h2>
            <p>Копия заказа выслана на Ваш почтовый ящик. Наши менеджеры свяжутся с Вами в ближайшее время для подтверждения заказа.</p>
            <div class="xs-margin"></div>
            <a href="<?=\yii\helpers\Url::toRoute('/site/index')?>" class="btn btn-custom-2" role="button">Продолжить</a>
        </div>
    </div>
</div>
