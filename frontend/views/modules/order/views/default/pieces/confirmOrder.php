<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 29.03.2018
 * Time: 13:46
 */

use common\modules\cart\widgets\CartInformer;
use common\modules\cart\widgets\ChangeCount;
use common\modules\cart\widgets\DeleteButton;
use common\modules\cart\widgets\ElementCost;
use common\modules\cart\widgets\ElementPrice;
use common\modules\order\forms\frontend\OrderForm;

/** @var \yii\web\View $this */
/** @var OrderForm $orderForm */
/** @var \common\modules\cart\models\CartElement[] $elements */
/** @var \yii\web\View $this */
$this->title = yii::t('cart', 'Cart');
/** @var \common\modules\catalog\CatalogModule $catalog */
$catalog = \Yii::$app->getModule('catalog');
/** @var \common\modules\cart\CartService $cartService */
$cartService = yii::$app->get('cartService');
$cost = $cartService->getCost(false);
$deliveryCost = OrderForm::DELIVERY_METHODS_PRICES[$orderForm->deliveryMethod];
/** @var \common\modules\files\FilesModule $filesManager */
$filesManager = Yii::$app->getModule('files')->manager;
$elements = $orderForm->cartElements;
$this->registerCss(
    '
.custom-quantity-input input[type=number]::-webkit-inner-spin-button,
.custom-quantity-input input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
');
?>
<div class="row">
    <div class="col-md-12">
        <header class="content-title">
            <h1 class="title">Ваш заказ</h1>
            <p class="title-desc">Проверьте и подтвердите Ваш заказ!</p>
        </header>
        <div class="xs-margin"></div><!-- space -->
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table checkout-table">
                    <thead>
                    <tr>
                        <th class="table-title">Наименование товара</th>
                        <th class="table-title">Код продукта</th>
                        <th class="table-title">Цена за единицу</th>
                        <th class="table-title">Количество</th>
                        <th class="table-title">Сумма</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($elements as $element): ?>
                        <?php
                        $model = $element->getModel();
                        $image = isset($model->images[0]) ? $model->images[0] : 'default.jpg';
                        $image = $filesManager->getFileUri('products/images', $image);
                        try {
                            $productUrl = $catalog->getCatalogUri(NULL, $model);
                        } catch(ErrorException $e) {
                            Yii::error($e->getMessage());
                            $productUrl = '';
                        }
                        $productName = $element->getModel()->getCartName();
                        $productPrice = ElementPrice::widget(['model' => $element]);
                        $productCost = ElementCost::widget(['model' => $element]);
                        ?>
                        <tr>
                            <td class="item-name-col">
                                <figure>
                                    <a href="<?=$productUrl?>"><img src="<?=$image?>" alt="<?=$productName?>"></a>
                                </figure>
                                <header class="item-name"><a href="<?=$productUrl?>"><?=$productName?></a></header>
                            </td>
                            <td class="item-code">1000<?=$model->id?></td>
                            <td class="item-price-col"><span class="item-price-special"><?=$productPrice?></span></td>
                            <td>
                                <div class="custom-quantity-input">
                                    <?=ChangeCount::widget(
                                        [
                                            'model' => $element,
                                            'upArr' => '<i class="fa fa-angle-up"></i>',
                                            'downArr' => '<i class="fa fa-angle-down"></i>',
                                            'upArrCssClass' => 'quantity-btn quantity-input-up',
                                            'downArrCssClass' => 'quantity-btn quantity-input-down',
                                        ]);?>
                                </div>
                            </td>
                            <td class="item-total-col"><span class="item-price-special"><?=$productCost?></span>
                                <?php DeleteButton::begin(
                                    [
                                        'model' => $element,
                                        'lineSelector' => '.table.cart-table > tbody > tr',
                                        'cssClass' => 'delete delete-item',
                                    ]) ?>
                                <i class="close-button"></i>
                                <?php DeleteButton::end(); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td class="checkout-table-title" colspan="4">И того по товару:</td>
                        <td class="checkout-table-price"><?=CartInformer::widget(['htmlTag' => 'span', 'text' => '{p}']);?></td>
                    </tr>
                    <?php if ($deliveryCost): ?>
                    <tr>
                        <td class="checkout-table-title" colspan="4">Доставка:</td>
                        <td class="checkout-table-price"><span class="shop-order-delivery" data-price="<?=$deliveryCost?>"><?=yii::$app->formatter->asCurrency($deliveryCost);?></span></td>
                    </tr>
                    <?php endif; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td class="checkout-total-title" colspan="4"><strong>Всего по заказу:</strong></td>
                        <td class="checkout-total-price cart-total"><strong class="shop-order-total"><?=yii::$app->formatter->asCurrency($cost + $deliveryCost);?></strong></td>
                    </tr>
                    </tfoot>
                </table>
                <div class="md-margin"></div><!-- End .space -->
                <?php $form = kartik\form\ActiveForm::begin(
                    [
                        'options' => [
                            'data' => [
                                'type' => 'stepForm',
                                'pjax' => 'true',
                            ],
                        ],
                    ]); ?>
                
                <?=$form->field($orderForm, 'orderStep', ['inputOptions' => ['value' => 4, 'id' => FALSE]])
                    ->hiddenInput()->label(FALSE)?>
                
                <?=\yii\helpers\Html::submitButton(
                    'Оформить заказ', [
                    'class' => 'btn btn-custom-2',
                ])?>
                
                <?php kartik\form\ActiveForm::end(); ?>
            </div><!-- End .col-md-12 -->
        </div><!-- End .row -->
    </div><!-- End .col-md-12 -->
</div><!-- End .row -->
