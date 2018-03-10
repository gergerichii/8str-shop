<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 25.12.2017
 * Time: 19:11
 */

use common\modules\cart\widgets\CartInformer;
use common\modules\cart\widgets\ElementsList;
use common\modules\catalog\widgets\MainSearchWidget;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

/** @var \yii\web\User $user */
$user = yii::$app->getUser();
/** @var \yii\web\UrlManager $adminUrlManager */
$adminUrlManager = Yii::$app->get('adminUrlManager');
?>

<header id="header" class="header6">
    <div id="header-top">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="header-top-left">
                        <ul id="top-links" class="clearfix">
<!--                            <li><a href="#" title="Избранные товары"><span class="top-icon top-icon-pencil"></span><span class="hide-for-xs">Избранные товары</span></a></li>-->
<!--                            <li><a href="#" title="Мой аккаунт"><span class="top-icon top-icon-user"></span><span class="hide-for-xs">Мой аккаунт</span></a></li>-->
                            <li><a href="<?=Url::toRoute('cart/default/index')?>" title="Корзина"><span class="top-icon top-icon-cart"></span><span  class="hide-for-xs">Корзина</span></a></li>
                            <li><a href="<?=Url::toRoute('order/default/index')?>" title="Оформить заказ"><span class="top-icon top-icon-check"></span><span class="hide-for-xs">Оформить заказ</span></a></li>
                        </ul>
                    </div><!-- End .header-top-left -->
                    <div class="header-top-right">
                        <div class="header-text-container pull-right">
                            <p class="header-link">
                                <?php if ($user->isGuest): ?>
                                    <a href="<?=Url::toRoute($user->loginUrl)?>">Вход</a>
                                    &nbsp;или&nbsp;
                                    <a href="<?=Url::toRoute('/site/signup')?>">Создать аккаунт</a>
                                <?php else: ?>
                                    <?php if($user->can('access_to_admin_panel')): ?>
                                        <a href="<?=$adminUrlManager->createAbsoluteUrl(['/'])?>">Админка</a> |
                                    <?php endif; ?>
                                    <a href="<?=Url::toRoute('/site/logout')?>">(<?= Yii::$app->user->getIdentity()->username ?>) Выход </a>
                                <?php endif; ?>
                            </p>
                        </div><!-- End .float-right -->
                    </div><!-- End .header-top-right -->
                </div><!-- End .col-md-12 -->
            </div><!-- End .row -->
        </div><!-- End .container -->
    </div><!-- End #header-top -->

    <!-- TODO: Перенести стиль в css-->
    <div id="inner-header" style="padding-top: 0px">
        <div class="container">
            <div class="row">
                                        <!-- TODO: Перенести стиль в css-->
                <div class="col-md-3 col-sm-3 col-xs-12 logo-container" style="margin-bottom: 0px">
                    <h1 class="logo clearfix">
                        <span>Responsive eCommerce Template</span>
                                                                                                                    <!-- TODO: Перенести стиль в css-->
                        <a href="/" title="Venedor eCommerce Template"><img src="/images/logo.jpg" alt="Venedor Commerce Template" style="width: 260px; margin-left: 7px"></a>
                    </h1>
                </div><!-- End .col-md-3 -->
                <!-- TODO: Перенести стиль в css-->
                <div class="col-md-9 col-sm-9 col-xs-12 header-inner-right" style="margin-top: 35px">
                    <div class="header-inner-right-wrapper clearfix">
                        <?php if(yii::$app->getModule('cart', false)): ?>
                            <div class="dropdown-cart-menu-container pull-right">
                            <div class="btn-group dropdown-cart">
                                <?php CartInformer::begin(); ?>
                                    <a href="{link}" type="button" class="btn dropdown-toggle">
                                        <span class="cart-menu-icon"></span>
                                        {c} шт. <span class="drop-price">- {p}</span>
                                    </a>
                                <?php CartInformer::end(); ?>

                                <div class="dropdown-menu dropdown-cart-menu pull-right clearfix" role="menu">
                                    <p class="dropdown-cart-description">Добавленные товары</p>
                                    
                                    <ul class="dropdown-cart-product-list">
                                        <?= ElementsList::widget([
                                            'elementView' => '//layouts/pieces/headerCartElement',
                                            'listOnly' => true,
                                            'showCountArrows' => false,
                                        ]) ?>
                                    </ul>

                                    <ul class="dropdown-cart-total">
                                        <?php CartInformer::begin(); ?>
                                            <li>
                                                <span class="dropdown-cart-total-title">Всего:</span>{p}
                                            </li>
                                        <?php CartInformer::end(); ?>
                                    </ul><!-- .dropdown-cart-total -->
                                    <div class="dropdown-cart-action">
                                        <p><a href="<?= Url::toRoute('/cart/default/index') ?>" class="btn btn-custom-2 btn-block">Корзина</a></p>
                                        <p><a href="<?= Url::toRoute('/order/default/index') ?>" class="btn
                                        btn-custom btn-block">Оформить</a></p>
                                    </div><!-- End .dropdown-cart-action -->
                                </div><!-- End .dropdown-cart -->
                            </div><!-- End .btn-group -->
                        </div><!-- End .dropdown-cart-menu-container -->
                        <?php endif; ?>

                        <div id="quick-access">
                            <?= MainSearchWidget::widget(); ?>
                        </div><!-- End #quick-access -->

                        <div class="header-box contact-infos pull-right">
                            <ul>
                                <li><span class="header-box-icon header-box-icon-skype"></span>venedor_support</li>
                                <li><span class="header-box-icon header-box-icon-email"></span><a href="mailto:venedor@gmail.com">venedor@gmail.com</a></li>
                            </ul>
                        </div><!-- End .contact-infos -->

                        <div class="header-box contact-phones pull-right clearfix">
                            <span class="header-box-icon header-box-icon-earphones"></span>
                            <ul class="pull-left">
                                <li>+(404) 158 14 25 78</li>
                                <li>+(404) 851 21 48 15</li>
                            </ul>
                        </div><!-- End .contact-phones -->

                    </div><!-- End .header-inner-right-wrapper -->
                </div><!-- End .col-md-7 -->
            </div><!-- End .row -->
        </div><!-- End .container -->

        <div id="main-nav-container">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 clearfix">

                        <nav id="main-nav">
                            <div id="responsive-nav">
                                <div id="responsive-nav-button">
                                    Menu <span id="responsive-nav-button-icon"></span>
                                </div><!-- responsive-nav-button -->
                            </div>
                            <div class="menu-table">
                                <ul class="menu clearfix">
                                <li>
                                    <a class="active" href="index.html">HOME</a>
                                    <ul>
                                        <li><a href="../green/index.html">Home 1</a></li>
                                        <li><a href="../blue/index.html">Home 2</a></li>
                                        <li><a href="../orange/index.html">Home 3</a></li>
                                        <li><a href="../pink/index.html">Home 4</a></li>
                                        <li><a href="../brown/index.html">Home 5</a></li>
                                        <li><a href="../green2/index.html">Home 6</a></li>
                                        <li><a href="../blueorange/index.html">Home 7</a></li>
                                        <li><a href="../blueorange2/index.html">Home 8</a></li>
                                        <li><a href="../browngreen/index.html">Home 9</a></li>
                                    </ul>
                                </li>
                                <li class="mega-menu-container"><a href="#">SHOP</a>
                                    <div class="mega-menu clearfix">
                                            <div class="col-5">
                                                <a href="category.html" class="mega-menu-title">Clothing</a><!-- End .mega-menu-title -->
                                                <ul class="mega-menu-list clearfix">
                                                    <li><a href="#">Dresses</a></li>
                                                    <li><a href="#">Jeans &amp; Trousers</a></li>
                                                    <li><a href="#">Blouses &amp; Shirts</a></li>
                                                    <li><a href="#">Tops &amp; T-Shirts</a></li>
                                                    <li><a href="#">Jackets &amp; Coats</a></li>
                                                    <li><a href="#">Skirts</a></li>
                                                </ul>
                                            </div><!-- End .col-5 -->
                                            <div class="col-5">
                                                <a href="category.html" class="mega-menu-title">Shoes</a><!-- End .mega-menu-title -->
                                                <ul class="mega-menu-list clearfix">
                                                    <li><a href="#">Formal Shoes</a></li>
                                                    <li><a href="#">Casual Shoes</a></li>
                                                    <li><a href="#">Sandals</a></li>
                                                    <li><a href="#">Boots</a></li>
                                                    <li><a href="#">Wide Fit</a></li>
                                                    <li><a href="#">Slippers</a></li>
                                                </ul>
                                            </div><!-- End .col-5 -->
                                            <div class="col-5">
                                                <a href="category.html" class="mega-menu-title">Accessories</a><!-- End .mega-menu-title -->
                                                <ul class="mega-menu-list clearfix">
                                                    <li><a href="#">Bags &amp; Purses</a></li>
                                                    <li><a href="#">Belts</a></li>
                                                    <li><a href="#">Gloves</a></li>
                                                    <li><a href="#">Jewellery</a></li>
                                                    <li><a href="#">Sunglasses</a></li>
                                                    <li><a href="#">Hair Accessories</a></li>
                                                </ul>
                                            </div><!-- End .col-5 -->
                                            <div class="col-5">
                                                <a href="category.html" class="mega-menu-title">Sports</a><!-- End .mega-menu-title -->
                                                <ul class="mega-menu-list clearfix">
                                                    <li><a href="#">Sport Tops &amp; Vests</a></li>
                                                    <li><a href="#">Swimwear</a></li>
                                                    <li><a href="#">Footwear</a></li>
                                                    <li><a href="#">Sports Underwear</a></li>
                                                    <li><a href="#">Bags</a></li>
                                                </ul>
                                            </div><!-- End .col-5 -->

                                            <div class="col-5">
                                                <a href="category.html" class="mega-menu-title">Maternity</a><!-- End .mega-menu-title -->
                                                <ul class="mega-menu-list clearfix">
                                                    <li><a href="#">Tops &amp; Skirts</a></li>
                                                    <li><a href="#">Dresses</a></li>
                                                    <li><a href="#">Trousers &amp; Shorts</a></li>
                                                    <li><a href="#">Knitwear</a></li>
                                                    <li><a href="#">Jackets &amp; Coats</a></li>
                                                </ul>
                                            </div><!-- End .col-5 -->
                                    </div><!-- End .mega-menu -->
                                </li>

                                <li>
                                    <a href="#">PAGES</a>
                                    <ul>
                                        <li><a href="#">Headers</a>
                                            <ul>
                                                <li><a href="header1.html">Header Version 1</a></li>
                                                <li><a href="header2.html">Header Version 2</a></li>
                                                <li><a href="header3.html">Header Version 3</a></li>
                                                <li><a href="header4.html">Header Version 4</a></li>
                                                <li><a href="header5.html">Header Version 5</a></li>
                                                <li><a href="header6.html">Header Version 6</a></li>
                                                <li><a href="header7.html">Header Version 7</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">Footers</a>
                                            <ul>
                                                <li><a href="footer1.html">Footer Version 1</a></li>
                                                <li><a href="footer2.html">Footer Version 2</a></li>
                                                <li><a href="footer3.html">Footer Version 3</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="product.html">Product</a></li>
                                        <li><a href="cart.html">Cart</a></li>
                                        <li><a href="category.html">Category</a>
                                            <ul>
                                                <li><a href="category-list.html">Category list</a></li>
                                                <li><a href="category.html">Category Banner 1</a></li>
                                                <li><a href="category-banner-2.html">Category Banner 2</a></li>
                                                <li><a href="category-banner-3.html">Category Banner 3</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="blog.html">Blog</a>
                                            <ul>
                                                <li><a href="blog.html">Right Sidebar</a></li>
                                                <li><a href="blog-sidebar-left.html">Left Sidebar</a></li>
                                                <li><a href="blog-sidebar-both.html">Both Sidebar</a></li>
                                                <li><a href="single.html">Blog Post</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="checkout.html">Checkout</a></li>
                                        <li><a href="aboutus.html">About Us</a></li>
                                        <li><a href="register-account.html">Register Account</a></li>
                                        <li><a href="compare-products.html">Compare Products</a></li>
                                        <li><a href="login.html">Login</a></li>
                                        <li><a href="404.html">404 Page</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Portfolio</a>
                                    <ul>
                                        <li><a href="#">Classic</a>
                                            <ul>
                                                <li><a href="portfolio-2.html">Two Columns</a></li>
                                                <li><a href="portfolio-3.html">Three Columns</a></li>
                                                <li><a href="portfolio-4.html">Four Columns</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">Masonry</a>
                                            <ul>
                                                <li><a href="portfolio-masonry-2.html">Two Columns</a></li>
                                                <li><a href="portfolio-masonry-3.html">Three Columns</a></li>
                                                <li><a href="portfolio-masonry-4.html">Four Columns</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">Portfolio Posts</a>
                                            <ul>
                                                <li><a href="single-portfolio.html">Image Post</a></li>
                                                <li><a href="single-portfolio-gallery.html">Gallery Post</a></li>
                                                <li><a href="single-portfolio-video.html">Video Post</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="#">Elements</a>
                                    <ul>
                                        <li><a href="elements/tabs.html">Tabs</a></li>
                                        <li><a href="elements/titles.html">Titles</a></li>
                                        <li><a href="elements/typography.html">Typography</a></li>
                                        <li><a href="elements/collapses.html">collapses</a></li>
                                        <li><a href="elements/animations.html">animations</a></li>
                                        <li><a href="elements/grids.html">Grid System</a></li>
                                        <li><a href="elements/alerts.html">Alert Boxes</a></li>
                                        <li><a href="elements/buttons.html">Buttons</a></li>
                                        <li><a href="elements/medias.html">Media</a></li>
                                        <li><a href="elements/forms.html">Forms</a></li>
                                        <li><a href="elements/icons.html">Icons</a></li>
                                        <li><a href="elements/lists.html">Lists</a></li>
                                        <li><a href="elements/more.html">More</a></li>
                                    </ul>
                                </li>
                                <li><a href="contact.html">Contact Us</a></li>
                                <li><a href="#">My Account</a></li>
                                <li><a href="#">News</a></li>
                                <li><a href="http://themeforest.net/item/venedor-premium-bootstrap-ecommerce-html5-template/7426521?ref=SW-THEMES" target="_blank">Buy Venedor</a></li>
                            </ul>
                            </div>

                        </nav>

                    </div><!-- End .col-md-12 -->
            </div><!-- End .row -->
        </div><!-- End .container -->

        </div><!-- End #nav -->
    </div><!-- End #inner-header -->
</header><!-- End #header -->

