<?php
use common\modules\cart\widgets\DeleteButton;
use common\modules\cart\widgets\TruncateButton;
use common\modules\cart\widgets\ChangeCount;
use common\modules\cart\widgets\CartInformer;
use common\modules\cart\widgets\ChangeOptions;

$this->title = yii::t('cart', 'Cart');
?>

<div class="cart">
    <h1><?= yii::t('cart', 'Cart'); ?></h1>
    <?php foreach($elements as $element) { ?>
        <div class="row">
            <div class="col-lg-6 col-xs-6">
                <strong><?=$element->getModel()->getCartName();?> (<?=$element->getModel()->getCartPrice();?> р.)</strong>
                <?=ChangeOptions::widget(['model' => $element, 'type' => 'radio']); ?>
            </div>
            <div class="col-lg-4 col-xs-4">
                <?=ChangeCount::widget(['model' => $element]);?>
            </div>
            <div class="col-lg-2 col-xs-2">
                <?=DeleteButton::widget(['model' => $element, 'lineSelector' => '.row']);?>
            </div>
        </div>
    <?php } ?>
    <div class="total">
        <?=CartInformer::widget(['htmlTag' => 'h3']); ?>
    </div>
</div>
