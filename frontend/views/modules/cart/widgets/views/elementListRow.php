<?php
use yii\helpers\Html;
use common\modules\cart\widgets\ChangeCount;
use common\modules\cart\widgets\DeleteButton;
use common\modules\cart\widgets\ElementPrice;
use common\modules\cart\widgets\ElementCost;

?>
------------------------------------
<li class="shop-cart-row ">
    <div class=" row">
        <div class="col-xs-8">
            <?= $name ?>

            <?php if ($options) {
                $productOptions = '';
                foreach ($options as $optionId => $valueId) {
                    if ($optionData = $allOptions[$optionId]) {
                        $option = $optionData['name'];
                        $value = $optionData['variants'][$valueId];
                        $productOptions .= Html::tag('div', Html::tag('strong', $option) . ':' . $value);
                    }
                }
                echo Html::tag('div', $productOptions, ['class' => 'shop-cart-show-options']);
            } ?>

            <?php if(!empty($otherFields)) {
                foreach($otherFields as $fieldName => $field) {
                    if(isset($product->$field)) echo Html::tag('p', Html::tag('small', $fieldName.': '.$product->$field));
                }
            } ?>
        </div>
        <div class="col-xs-3">
            <?= ElementPrice::widget(['model' => $model]); ?>

            <?= ChangeCount::widget([
                'model' => $model,
                'showArrows' => $showCountArrows,
                'actionUpdateUrl' => $controllerActions['update'],
            ]); ?>

        </div>

        <?= Html::tag('div', DeleteButton::widget([
            'model' => $model,
            'deleteElementUrl' => $controllerActions['delete'],
            'lineSelector' => 'shop-cart-row ',
            'cssClass' => 'delete']),
            ['class' => 'shop-cart-delete col-xs-1']);
        ?>
    </div>
</li>
