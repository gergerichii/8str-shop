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
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="row">

                <?= $this->render('product/imagesPreview', ['product' => $productModel]) ?>

                <div class="col-md-6 col-sm-12 col-xs-12 product">
                    <div class="lg-margin visible-sm visible-xs"></div><!-- Space -->
                    <h1 class="product-name"><?=$productModel?></h1>
                    <ul class="product-list">
                        <li><span>Наличие:</span>
                            <?=($productModel->count) ? 'В наличии' : "В наличии на складе. Доставка {$productModel->delivery_time} дней."?>
                        </li>
                        <li><span>Торговая марка:</span><?=$productModel->brand?></li>
                    </ul>
                    <hr>
                    <div class="product-add clearfix">
                        <div class="custom-quantity-input">
                            <input type="text" name="quantity" value="1">
                            <a href="#" onclick="return false;" class="quantity-btn quantity-input-up"><i class="fa fa-angle-up"></i></a>
                            <a href="#" onclick="return false;" class="quantity-btn quantity-input-down"><i class="fa fa-angle-down"></i></a>
                        </div>
                        <button class="btn btn-custom-2">ADD TO CART</button>
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

<!--                    TODO: Тут будет цикл по разным параметрам чтобы генерить разные табы-->
                    <div class="tab-container left product-detail-tab clearfix">
                        <?=$productModel->desc?>

                    </div><!-- End .tab-container -->
                    <div class="lg-margin visible-xs"></div>
                </div><!-- End .col-md-9 -->
            </div><!-- End .row -->

        </div><!-- End .col-md-12 -->
    </div><!-- End .row -->
</div><!-- End .container -->

