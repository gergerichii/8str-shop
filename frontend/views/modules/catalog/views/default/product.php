<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 13.01.2018
 * Time: 17:25
 */

/** @var \yii\web\View $this */
/** @var \common\modules\catalog\models\Product $productModel */
?>

<?php common\helpers\ViewHelper::startRegisterCss($this); ?>
<style>
    .custom-quantity-input input[type=number]::-webkit-inner-spin-button,
    .custom-quantity-input input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    .custom-quantity-input input[type='number'] {
        -moz-appearance: textfield;
    }
    
    .tabs-left > .nav-tabs > .active > a,
    .tabs-left > .nav-tabs > li.active > a:hover,
    .tabs-left > .nav-tabs > li.active > a:focus {
        border-color: transparent !important;
    }
    .tabs-left > .nav-tabs > li > a {
        margin-right: unset !important;
        -webkit-border-radius: unset !important;
        border-radius: unset !important;
    }
    
    .tabs-left > .nav-tabs > li > a,
    .tabs-right > .nav-tabs > li > a {
        margin-bottom: unset !important;
    }
    
    .tabs-left > .nav-tabs > li > a:hover {
        border-color: transparent !important;
    }
</style>
<?php common\helpers\ViewHelper::endRegisterCss(); ?>


<div class="container">
    <div class="row">
        <div class="main-content col-sm-12 col-md-12">

            <div class="content row" role="main">
                <div class="col-md-6 col-sm-12 col-xs-12 product-thumbs">
                    <?= $this->render('product/imagePreviewNew', ['product' => $productModel]) ?>
                </div>

                <div class="col-md-6 col-sm-12 col-xs-12 product">
                    <div class="lg-margin visible-sm visible-xs"></div><!-- Space -->
                    <h1 class="product-name"><?=$productModel?></h1>
                    <!-- TODO: Убрать css в файл стилей -->
                    <?php if ($productModel->short_desc): ?>
                        <div itemprop="description" class="description" style="border-bottom: 1px solid #e8e8e8; margin: 15px 0; padding-bottom: 25px;" >
                            <p><?=$productModel->short_desc?></p>
                        </div>
                    <?php endif; ?>
                    <ul class="product-list">
                        <li><span>Наличие:</span>
                            <?=($productModel->count) ? 'В наличии' : "В наличии на складе. Доставка до {$productModel->delivery_time}"?>
                            <?php if (!$productModel->count): ?>
                                <?=Yii::$app->i18n->format('{n, plural, =0{дней} =1{день} one{дня} other{дней}}.', ['n' => $productModel->delivery_time], 'ru_RU')?>
                            <?php endif; ?>
                        </li>
                        <li><span>Торговая марка:</span><?=$productModel->brand?></li>
                    </ul>
                    <hr>
                    <div class="product-add clearfix">
                        <div class="custom-quantity-input">
                            <input type="number" name="quantity" value="1" class="product-change-count" data-id="<?=$productModel->id?>" step="1" min="1">
                            <span class="quantity-btn quantity-input-up"><i class="fa fa-angle-up"></i></span>
                            <span href="" class="quantity-btn quantity-input-down"><i class="fa fa-angle-down"></i></span>
                        </div>
                        <?php \common\modules\cart\widgets\BuyButton::begin([
                                'model' => $productModel,
                                'cssClass' => 'btn btn-custom-2 ',
                        ])?>
                            В корзину
                        <?php \common\modules\cart\widgets\BuyButton::end() ?>
                    </div><!-- .product-add -->
                    <div class="md-margin"></div><!-- Space -->
                    <div class="product-extra clearfix">
                        <div class="md-margin visible-xs"></div>
                    </div>
                </div><!-- End .col-md-6 -->
            </div>

            <div class="hide-less-lg lg-margin2x"></div><!-- End .space -->

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <?= \common\modules\catalog\widgets\ProductDescriptionWidget::widget([
                        'model' => $productModel,
                    ]) ?>
                </div><!-- End .col-md-9 -->
            </div><!-- End .row -->

        </div><!-- End .col-md-12 -->
    </div><!-- End .row -->
</div><!-- End .container -->

<?php common\helpers\ViewHelper::startRegisterScript($this); ?>
<script>
    $(function() {
        $(document).delegate('input.product-change-count', 'change', function() {
            var id = $(this).data('id');
            $('a.shop-cart-buy-button[data-id="'+ id + '"]').data('count', $(this).val());
        });
        
        $(document).delegate('.product-add .quantity-btn', 'click', function(){
            var input = $(this).siblings('.product-change-count').get(0);
            if ($(this).hasClass('quantity-input-up')) {
                $(input).val(1 + $(input).val() * 1);
            } else if ($(this).hasClass('quantity-input-down') && ($(input).val() * 1) !== 1) {
                $(input).val($(input).val() - 1);
            }
            var id = $(input).data('id');
            $('a.shop-cart-buy-button[data-id="'+ id + '"]').data('count', $(input).val());
            return false;
        });
    });
</script>
<?php common\helpers\ViewHelper::endRegisterScript(); ?>
