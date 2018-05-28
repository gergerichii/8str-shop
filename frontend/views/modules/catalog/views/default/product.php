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
        <div class="col-md-12">

            <div class="row">

                <?= $this->render('product/imagesPreview', ['product' => $productModel]) ?>

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
                            <?=Yii::$app->i18n->format('{n, plural, =0{дней} =1{день} one{дня} other{дней}}.', ['n' => $productModel->delivery_time], 'ru_RU')?>
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
                <!-- TODO: Сопутствующие товары ставим сюда и не по колонкам а по строкам -->


            </div><!-- End .row -->

            <div class="lg-margin2x"></div><!-- End .space -->

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <?php $tabs = [
                        [
                            'label' => 'Описание',
                            'content' => $productModel->desc,
                            'active' => true,
                        ]
                    ] ?>
                   <?php $tabs[] = [
                        'label' => 'Технические характеристики',
                        'content' => ($productModel->tech_desc) ? $productModel->tech_desc : '',
                        'linkOptions' => [
                            'disabled' => ((bool) $productModel->tech_desc) ? '' : 'disabled',
                            'aria-disabled' => ((bool)$productModel->tech_desc) ? 'false' : 'true',
                            'class' => ((bool) $productModel->tech_desc) ? '' : 'disabled',
                            'style' => ((bool) $productModel->tech_desc) ? '' : 'pointer-events: none; color: #dcdcdc !important',
                        ],
                    ]; ?>
                    <?=\kartik\tabs\TabsX::widget([
                        'items' => $tabs,
                        'position'=>null,
                        'align'=>null,
                        'encodeLabels'=>true,
                        'bordered' => false,
                        'pluginOptions' => [
                            'addCss' => ''
                        ],
                        'containerOptions' => [
                            'class' => ['tab-container', 'left', 'product-detail-tab', 'clearfix'],
                        ],
                        'options' => [
                            'style' => 'height: 378px'
                        ]
                    ]) ?>
                    
<!--                    TODO: Тут будет цикл по разным параметрам чтобы генерить разные табы-->
<!--                    <div class="tab-container left product-detail-tab clearfix">-->
<!--                        --><?//=$productModel->desc?>
<!---->
<!--                    </div><!-- End .tab-container -->
                    <div class="lg-margin visible-xs"></div>
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
