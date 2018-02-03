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
                        <div class="product-extra-box-container clearfix">
                            <div class="item-action-inner">
                                <a href="#" class="icon-button icon-like">Favourite</a>
                                <a href="#" class="icon-button icon-compare">Checkout</a>
                            </div><!-- End .item-action-inner -->
                        </div>
                        <div class="md-margin visible-xs"></div>
                    </div>
                </div><!-- End .col-md-6 -->


            </div><!-- End .row -->

            <div class="lg-margin2x"></div><!-- End .space -->

            <div class="row">
                <div class="col-md-9 col-sm-12 col-xs-12">

<!--                    TODO: Тут будет цикл по разным параметрам чтобы генерить разные табы-->
                    <div class="tab-container left product-detail-tab clearfix">
                        <?=$productModel->desc?>

                    </div><!-- End .tab-container -->
                    <div class="lg-margin visible-xs"></div>
                </div><!-- End .col-md-9 -->
                <div class="lg-margin2x visible-sm visible-xs"></div><!-- Space -->
                <div class="col-md-3 col-sm-12 col-xs-12 sidebar">
                    <div class="widget related">
                        <h3>Related</h3>

                        <div class="related-slider flexslider sidebarslider">
                            <ul class="related-list clearfix">
                                <li>
                                    <div class="related-product clearfix">
                                        <figure>
                                            <img src="/images/products/thumbnails/item1.jpg" alt="item1">
                                        </figure>
                                        <h5><a href="#">Jacket Suiting Blazer</a></h5>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="84"></div>
                                            </div><!-- End .ratings -->
                                        </div><!-- End .rating-container -->
                                        <div class="related-price">$40</div><!-- End .related-price -->
                                    </div><!-- End .related-product -->

                                    <div class="related-product clearfix">
                                        <figure>
                                            <img src="/images/products/thumbnails/item2.jpg" alt="item2">
                                        </figure>
                                        <h5><a href="#">Gap Graphic Cuffed</a></h5>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="84"></div>
                                            </div><!-- End .ratings -->
                                        </div><!-- End .rating-container -->
                                        <div class="related-price">18$</div><!-- End .related-price -->
                                    </div><!-- End .related-product -->

                                    <div class="related-product clearfix">
                                        <figure>
                                            <img src="/images/products/thumbnails/item3.jpg" alt="item3">
                                        </figure>
                                        <h5><a href="#">Women's Lauren Dress</a></h5>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="84"></div>
                                            </div><!-- End .ratings -->
                                        </div><!-- End .rating-container -->
                                        <div class="related-price">$30</div><!-- End .related-price -->
                                    </div><!-- End .related-product -->
                                </li>
                                <li>
                                    <div class="related-product clearfix">
                                        <figure>
                                            <img src="/images/products/thumbnails/item4.jpg" alt="item4">
                                        </figure>
                                        <h5><a href="#">Swiss Mobile Phone</a></h5>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="64"></div>
                                            </div><!-- End .ratings -->
                                        </div><!-- End .rating-container -->
                                        <div class="related-price">$39</div><!-- End .related-price -->
                                    </div><!-- End .related-product -->

                                    <div class="related-product clearfix">
                                        <figure>
                                            <img src="/images/products/thumbnails/item5.jpg" alt="item5">
                                        </figure>
                                        <h5><a href="#">Zwinzed HeadPhones</a></h5>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="94"></div>
                                            </div><!-- End .ratings -->
                                        </div><!-- End .rating-container -->
                                        <div class="related-price">$18.99</div><!-- End .related-price -->
                                    </div><!-- End .related-product -->

                                    <div class="related-product clearfix">
                                        <figure>
                                            <img src="/images/products/thumbnails/item6.jpg" alt="item6">
                                        </figure>
                                        <h5><a href="#">Kless Man Suit</a></h5>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="74"></div>
                                            </div><!-- End .ratings -->
                                        </div><!-- End .rating-container -->
                                        <div class="related-price">$99</div><!-- End .related-price -->
                                    </div><!-- End .related-product -->
                                </li>
                                <li>

                                    <div class="related-product clearfix">
                                        <figure>
                                            <img src="/images/products/thumbnails/item2.jpg" alt="item2">
                                        </figure>
                                        <h5><a href="#">Gap Graphic Cuffed</a></h5>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="84"></div>
                                            </div><!-- End .ratings -->
                                        </div><!-- End .rating-container -->
                                        <div class="related-price">$17</div><!-- End .related-price -->
                                    </div><!-- End .related-product -->

                                    <div class="related-product clearfix">
                                        <figure>
                                            <img src="/images/products/thumbnails/item4.jpg" alt="item4">
                                        </figure>
                                        <h5><a href="#">Women's Lauren Dress</a></h5>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="84"></div>
                                            </div><!-- End .ratings -->
                                        </div><!-- End .rating-container -->
                                        <div class="related-price">$30</div><!-- End .related-price -->
                                    </div><!-- End .related-product -->
                                </li>
                            </ul>
                        </div><!-- End .related-slider -->
                    </div><!-- End .widget -->

                </div><!-- End .col-md-4 -->
            </div><!-- End .row -->
            <div class="lg-margin2x"></div><!-- Space -->
            <div class="purchased-items-container carousel-wrapper">
                <header class="content-title">
                    <div class="title-bg">
                        <h2 class="title">Also Purchased</h2>
                    </div><!-- End .title-bg -->
                    <p class="title-desc">Note the similar products - after buying for more than $500 you can get a discount.</p>
                </header>

                <div class="carousel-controls">
                    <div id="purchased-items-slider-prev" class="carousel-btn carousel-btn-prev"></div><!-- End .carousel-prev -->
                    <div id="purchased-items-slider-next" class="carousel-btn carousel-btn-next carousel-space"></div><!-- End .carousel-next -->
                </div><!-- End .carousel-controllers -->
                <div class="purchased-items-slider owl-carousel">
                    <div class="item item-hover">
                        <div class="item-image-wrapper">
                            <figure class="item-image-container">
                                <a href="product.html">
                                    <img src="/images/products/item7.jpg" alt="item1" class="item-image">
                                    <img src="/images/products/item7-hover.jpg" alt="item1  Hover" class="item-image-hover">
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
                                    <img src="/images/products/item8.jpg" alt="item1" class="item-image">
                                    <img src="/images/products/item8-hover.jpg" alt="item1  Hover" class="item-image-hover">
                                </a>
                            </figure>
                            <div class="item-price-container">
                                <span class="item-price">$100</span>
                            </div><!-- End .item-price-container -->
                            <span class="new-rect">New</span>
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
                                    <img src="/images/products/item9.jpg" alt="item1" class="item-image">
                                    <img src="/images/products/item9-hover.jpg" alt="item1  Hover" class="item-image-hover">
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
                                    <img src="/images/products/item6.jpg" alt="item1" class="item-image">
                                    <img src="/images/products/item6-hover.jpg" alt="item1  Hover" class="item-image-hover">
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
                                    <img src="/images/products/item7.jpg" alt="item1" class="item-image">
                                    <img src="/images/products/item7-hover.jpg" alt="item1  Hover" class="item-image-hover">
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

                    <div class="item item-hover">
                        <div class="item-image-wrapper">
                            <figure class="item-image-container">
                                <a href="product.html">
                                    <img src="/images/products/item10.jpg" alt="item1" class="item-image">
                                    <img src="/images/products/item10-hover.jpg" alt="item1  Hover" class="item-image-hover">
                                </a>
                            </figure>
                            <div class="item-price-container">
                                <span class="old-price">$150</span>
                                <span class="item-price">$120</span>
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

        </div><!-- End .col-md-12 -->
    </div><!-- End .row -->
</div><!-- End .container -->

