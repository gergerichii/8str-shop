<?php

use common\modules\cart\widgets\DeleteButton;
use common\modules\cart\widgets\ElementCost;
use common\modules\cart\widgets\ElementPrice;
use common\modules\cart\widgets\ElementsList;
use common\modules\cart\widgets\TruncateButton;
use common\modules\cart\widgets\ChangeCount;
use common\modules\cart\widgets\CartInformer;
use common\modules\cart\widgets\ChangeOptions;
use yii\helpers\Url;

/** @var \common\modules\cart\models\CartElement[] $elements */
/** @var \yii\web\View $this */
$this->title = yii::t('cart', 'Cart');
/** @var \common\modules\catalog\Module $catalog */
$catalog = \Yii::$app->getModule('catalog');

$this->registerCss('
.custom-quantity-input input[type=number]::-webkit-inner-spin-button,
.custom-quantity-input input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
');

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <header class="content-title">
                <h1 class="title"><?= $this->title ?></h1>
                <p class="title-desc">Ваш правилный выбор!</p>
            </header>
            <div class="xs-margin"></div><!-- space -->
            <div class="row">
                <div class="col-md-12 table-responsive">
                <?php if (!count($elements)): ?>
                    <b>Корзина пуста</b>
                <?php else: ?>
                    <table class="table cart-table">
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
                            $image = \common\modules\files\Module::getImageUri($image);
                            try{
                                $productUrl = $catalog->getCatalogUri(NULL, $model);
                            } catch(ErrorException $e){
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
                                        <a href="<?=$productUrl?>"><img src="<?=$image?>" alt="<?= $productName ?>"></a>
                                    </figure>
                                    <header class="item-name"><a href="<?=$productUrl?>"><?= $productName ?></a></header>
                                </td>
                                <td class="item-code">1000<?= $model->id ?></td>
                                <td class="item-price-col"><span class="item-price-special"><?=$productPrice?></span></td>
                                <td>
                                    <div class="custom-quantity-input">
                                        <?=ChangeCount::widget([
                                            'model' => $element,
                                            'upArr' => '<i class="fa fa-angle-up"></i>',
                                            'downArr' => '<i class="fa fa-angle-down"></i>',
                                            'upArrCssClass' => 'quantity-btn quantity-input-up',
                                            'downArrCssClass' => 'quantity-btn quantity-input-down',
                                        ]);?>
                                    </div>
                                </td>
                                <td class="item-total-col"><span class="item-price-special"><?=$productCost?></span>
                                    <?php DeleteButton::begin([
                                        'model' => $element,
                                        'lineSelector' => '.table.cart-table > tbody > tr',
                                        'cssClass' => 'delete delete-item',
                                    ])?>
                                        <i class="close-button"></i>
                                    <?php DeleteButton::end(); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                </div><!-- End .col-md-12 -->
            </div><!-- End .row -->
            <div class="lg-margin"></div><!-- End .space -->
            
            <?php if (count($elements)): ?>
                <div class="row">
                    <div class="col-md-8 col-sm-12 col-xs-12 lg-margin">
    
                        <div class="tab-container left clearfix">
                            <ul class="nav-tabs">
                                <li class="active"><a href="#discount" data-toggle="tab">Промо код</a></li>
                                <li><a href="#gift" data-toggle="tab">Подарочный сертификат</a></li>
    
                            </ul>
                            <div class="tab-content clearfix">
                                <div class="tab-pane active" id="discount">
                                    <p>Введите Ваш промокод</p>
                                    <form action="#">
                                        <div class="input-group">
                                            <input type="text" required class="form-control" placeholder="Промокод">
    
                                        </div><!-- End .input-group -->
                                        <input type="submit" class="btn btn-custom-2" value="Применить код">
                                    </form>
                                </div><!-- End .tab-pane -->
    
                                <div class="tab-pane" id="gift">
                                    <p>Введите номер подарочного сертификата</p>
                                    <form action="#">
                                        <div class="input-group">
                                            <input type="text" required class="form-control" placeholder="Код подарочного сертификата">
    
                                        </div><!-- End .input-group -->
                                        <input type="submit" class="btn btn-custom-2" value="Применить сертификат">
                                    </form>
                                </div><!-- End .tab-pane -->
    
                            </div><!-- End .tab-content -->
                        </div><!-- End .tab-container -->
    
                    </div><!-- End .col-md-8 -->
    
                    <div class="col-md-4 col-sm-12 col-xs-12">
    
                        <table class="table total-table">
                            <tbody>
                            <tr style="display: none">
                                <td class="total-table-title">Скидка:</td>
                                <td></td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td>Всего:</td>
                                <td><?=CartInformer::widget(['htmlTag' => 'span', 'text' => '{p}']);?></td>
                            </tr>
                            </tfoot>
                        </table>
                        <div class="md-margin"></div><!-- End .space -->
                        <a href="<?=Url::toRoute('/catalog/default/index')?>" class="btn btn-custom-2">Продолжить покупки</a>
                        <a href="<?= Url::toRoute('/order/default/index') ?>" class="btn btn-custom">Оформить заказ</a>
                    </div><!-- End .col-md-4 -->
                </div><!-- End .row -->
                <div class="md-margin2x"></div><!-- Space -->
    
                <div class="similiar-items-container carousel-wrapper">
                    <header class="content-title">
                        <div class="title-bg">
                            <h2 class="title">Similiar Products</h2>
                        </div><!-- End .title-bg -->
                        <p class="title-desc">Note the similar products - after buying for more than $500 you can get a
                            discount.</p>
                    </header>
    
                    <div class="carousel-controls">
                        <div id="similiar-items-slider-prev" class="carousel-btn carousel-btn-prev"></div>
                        <!-- End .carousel-prev -->
                        <div id="similiar-items-slider-next" class="carousel-btn carousel-btn-next carousel-space"></div>
                        <!-- End .carousel-next -->
                    </div><!-- End .carousel-controls -->
                    <div class="similiar-items-slider owl-carousel">
                        <div class="item item-hover">
                            <div class="item-image-wrapper">
                                <figure class="item-image-container">
                                    <a href="product.html">
                                        <img src="images/products/item3.jpg" alt="item1" class="item-image">
                                        <img src="images/products/item3-hover.jpg" alt="item1  Hover"
                                             class="item-image-hover">
                                    </a>
                                </figure>
                                <div class="item-price-container">
                                    <span class="item-price">$160<span class="sub-price">.99</span></span>
                                </div><!-- End .item-price-container -->
                                <span class="new-rect">New</span>
                            </div><!-- End .item-image-wrapper -->
                            <div class="item-meta-container">
                                <div class="ratings-container">
                                    <div class="ratings">
                                        <div class="ratings-result" data-result="80"></div>
                                    </div><!-- End .ratings -->
                                    <span class="ratings-amount">
                                                        5 Reviews
                                                    </span>
                                </div><!-- End .rating-container -->
                                <h3 class="item-name"><a href="product.html">Phasellus consequat</a></h3>
                                <div class="item-action">
                                    <a href="#" class="item-add-btn">
                                        <span class="icon-cart-text">Add to Cart</span>
                                    </a>
                                    <div class="item-action-inner">
                                        <a href="#" class="icon-button icon-like">Favourite</a>
                                        <a href="#" class="icon-button icon-compare">Checkout</a>
                                    </div><!-- End .item-action-inner -->
                                </div><!-- End .item-action -->
                            </div><!-- End .item-meta-container -->
                        </div><!-- End .item -->
    
                        <div class="item item-hover">
                            <div class="item-image-wrapper">
                                <figure class="item-image-container">
                                    <a href="product.html">
                                        <img src="images/products/item1.jpg" alt="item1" class="item-image">
                                        <img src="images/products/item1-hover.jpg" alt="item1  Hover"
                                             class="item-image-hover">
                                    </a>
                                </figure>
                                <div class="item-price-container">
                                    <span class="item-price">$100</span>
                                </div><!-- End .item-price-container -->
                            </div><!-- End .item-image-wrapper -->
                            <div class="item-meta-container">
                                <div class="ratings-container">
                                    <div class="ratings">
                                        <div class="ratings-result" data-result="99"></div>
                                    </div><!-- End .ratings -->
                                    <span class="ratings-amount">
                                                        4 Reviews
                                                    </span>
                                </div><!-- End .rating-container -->
                                <h3 class="item-name"><a href="product.html">Phasellus consequat</a></h3>
                                <div class="item-action">
                                    <a href="#" class="item-add-btn">
                                        <span class="icon-cart-text">Add to Cart</span>
                                    </a>
                                    <div class="item-action-inner">
                                        <a href="#" class="icon-button icon-like">Favourite</a>
                                        <a href="#" class="icon-button icon-compare">Checkout</a>
                                    </div><!-- End .item-action-inner -->
                                </div><!-- End .item-action -->
                            </div><!-- End .item-meta-container -->
                        </div><!-- End .item -->
    
                        <div class="item item-hover">
                            <div class="item-image-wrapper">
                                <figure class="item-image-container">
                                    <a href="product.html">
                                        <img src="images/products/item4.jpg" alt="item1" class="item-image">
                                        <img src="images/products/item4-hover.jpg" alt="item1  Hover"
                                             class="item-image-hover">
                                    </a>
                                </figure>
                                <div class="item-price-container">
                                    <span class="old-price">$100</span>
                                    <span class="item-price">$80</span>
                                </div><!-- End .item-price-container -->
                                <span class="discount-rect">-20%</span>
                            </div><!-- End .item-image-wrapper -->
                            <div class="item-meta-container">
                                <div class="ratings-container">
                                    <div class="ratings">
                                        <div class="ratings-result" data-result="75"></div>
                                    </div><!-- End .ratings -->
                                    <span class="ratings-amount">
                                                        2 Reviews
                                                    </span>
                                </div><!-- End .rating-container -->
                                <h3 class="item-name"><a href="product.html">Phasellus consequat</a></h3>
                                <div class="item-action">
                                    <a href="#" class="item-add-btn">
                                        <span class="icon-cart-text">Add to Cart</span>
                                    </a>
                                    <div class="item-action-inner">
                                        <a href="#" class="icon-button icon-like">Favourite</a>
                                        <a href="#" class="icon-button icon-compare">Checkout</a>
                                    </div><!-- End .item-action-inner -->
                                </div><!-- End .item-action -->
                            </div><!-- End .item-meta-container -->
                        </div><!-- End .item -->
    
                        <div class="item item-hover">
                            <div class="item-image-wrapper">
                                <figure class="item-image-container">
                                    <a href="product.html">
                                        <img src="images/products/item10.jpg" alt="item1" class="item-image">
                                        <img src="images/products/item10-hover.jpg" alt="item1  Hover"
                                             class="item-image-hover">
                                    </a>
                                </figure>
                                <div class="item-price-container">
                                    <span class="old-price">$120</span>
                                    <span class="item-price">$60</span>
                                </div><!-- End .item-price-container -->
                                <span class="discount-rect">-50%</span>
                            </div><!-- End .item-image-wrapper -->
                            <div class="item-meta-container">
                                <div class="ratings-container">
                                    <div class="ratings">
                                        <div class="ratings-result" data-result="65"></div>
                                    </div><!-- End .ratings -->
                                    <span class="ratings-amount">
                                                        4 Reviews
                                                    </span>
                                </div><!-- End .rating-container -->
                                <h3 class="item-name"><a href="product.html">Phasellus consequat</a></h3>
                                <div class="item-action">
                                    <a href="#" class="item-add-btn">
                                        <span class="icon-cart-text">Add to Cart</span>
                                    </a>
                                    <div class="item-action-inner">
                                        <a href="#" class="icon-button icon-like">Favourite</a>
                                        <a href="#" class="icon-button icon-compare">Checkout</a>
                                    </div><!-- End .item-action-inner -->
                                </div><!-- End .item-action -->
                            </div><!-- End .item-meta-container -->
                        </div><!-- End .item -->
    
                        <div class="item item-hover">
                            <div class="item-image-wrapper">
                                <figure class="item-image-container">
                                    <a href="product.html">
                                        <img src="images/products/item5.jpg" alt="item1" class="item-image">
                                        <img src="images/products/item5-hover.jpg" alt="item1  Hover"
                                             class="item-image-hover">
                                    </a>
                                </figure>
                                <div class="item-price-container">
                                    <span class="item-price">$99</span>
                                </div><!-- End .item-price-container -->
                                <span class="new-rect">New</span>
                            </div><!-- End .item-image-wrapper -->
                            <div class="item-meta-container">
                                <div class="ratings-container">
                                    <div class="ratings">
                                        <div class="ratings-result" data-result="40"></div>
                                    </div><!-- End .ratings -->
                                    <span class="ratings-amount">
                                                        3 Reviews
                                                    </span>
                                </div><!-- End .rating-container -->
                                <h3 class="item-name"><a href="product.html">Phasellus consequat</a></h3>
                                <div class="item-action">
                                    <a href="#" class="item-add-btn">
                                        <span class="icon-cart-text">Add to Cart</span>
                                    </a>
                                    <div class="item-action-inner">
                                        <a href="#" class="icon-button icon-like">Favourite</a>
                                        <a href="#" class="icon-button icon-compare">Checkout</a>
                                    </div><!-- End .item-action-inner -->
                                </div><!-- End .item-action -->
                            </div><!-- End .item-meta-container -->
                        </div><!-- End .item -->
    
                        <div class="item item-hover">
                            <div class="item-image-wrapper">
                                <figure class="item-image-container">
                                    <a href="product.html">
                                        <img src="images/products/item7.jpg" alt="item1" class="item-image">
                                        <img src="images/products/item7-hover.jpg" alt="item1  Hover"
                                             class="item-image-hover">
                                    </a>
                                </figure>
                                <div class="item-price-container">
                                    <span class="item-price">$280</span>
                                </div><!-- End .item-price-container -->
                            </div><!-- End .item-image-wrapper -->
                            <div class="item-meta-container">
                                <div class="ratings-container">
                                </div><!-- End .rating-container -->
                                <h3 class="item-name"><a href="product.html">Phasellus consequat</a></h3>
                                <div class="item-action">
                                    <a href="#" class="item-add-btn">
                                        <span class="icon-cart-text">Add to Cart</span>
                                    </a>
                                    <div class="item-action-inner">
                                        <a href="#" class="icon-button icon-like">Favourite</a>
                                        <a href="#" class="icon-button icon-compare">Checkout</a>
                                    </div><!-- End .item-action-inner -->
                                </div><!-- End .item-action -->
                            </div><!-- End .item-meta-container -->
                        </div><!-- End .item -->
    
                    </div><!--purchased-items-slider -->
                </div><!-- End .purchased-items-container -->
            <?php endif; ?>

        </div><!-- End .col-md-12 -->
    </div><!-- End .row -->
</div><!-- End .container -->


<?php return; ?>
<div class="cart">
    <h1><?=yii::t('cart', 'Cart');?></h1>
    <?php foreach($elements as $element) { ?>
        <div class="row">
            <div class="col-lg-6 col-xs-6">
                <strong><?=$element->getModel()->getCartName();?> (<?=$element->getModel()->getCartPrice();?>
                    р.)</strong>
                <?=ChangeOptions::widget(['model' => $element, 'type' => 'radio']);?>
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
        <?=CartInformer::widget(['htmlTag' => 'h3']);?>
    </div>
</div>
