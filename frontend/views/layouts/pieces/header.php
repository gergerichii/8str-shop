<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 25.12.2017
 * Time: 19:11
 */

use yii\helpers\Url;
use frontend\widgets\TopMenu;
use common\modules\cart\widgets\CartInformer;
use common\modules\cart\widgets\ElementsList;
use common\modules\catalog\widgets\MainSearchWidget;

/* @var $this \yii\web\View */
/* @var $content string */

/** @var \yii\web\User $user */
$user = yii::$app->getUser();
/** @var \yii\web\UrlManager $adminUrlManager */
$adminUrlManager = Yii::$app->get('adminUrlManager');
$cartService = yii::$app->get('cartService');
$count = $cartService->getCount();
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
                            <li>
                                <a href="<?=Url::toRoute('/cart/default/index')?>" title="Корзина"><span  class="top-icon top-icon-cart"></span><span  class="hide-for-xs">Корзина</span></a>
                            </li>
                            <li style="display: <?= ($count) ? 'initial' : 'none' ?>;" class="shop-cart-checkout-btn">
                                <a href="<?=Url::toRoute('/order/default/index')?>" title="Оформить заказ"><span class="top-icon top-icon-check"></span><span class="hide-for-xs">Оформить заказ</span></a>
                            </li>
                        </ul>
                    </div><!-- End .header-top-left -->
                    <div class="header-top-right">
                        <div class="header-text-container pull-right">
                            <p class="header-link">
                                <?php if ($user->isGuest): ?>
                                    <a href="<?=Url::toRoute('/site/login')?>">Вход</a>
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
                <div class="col-md-3 col-sm-3 col-xs-12 logo-container">
                    <h1 class="logo clearfix">
                        <span>Responsive eCommerce Template</span>
                        <!-- TODO: Перенести стиль в css-->
                        <a href="/" title="<?=Yii::$app->name?>">
                            <?php $logo = (isset(Yii::$app->params['logo'])) ? Yii::$app->params['logo'] : 'logo8@2x.png';?>
                            <img src="<?=Yii::getAlias("@web/images/{$logo}")?>" alt="<?=Yii::$app->name?>" style="width: 260px; margin-left: 7px">
                        </a>
                    </h1>
                </div><!-- End .col-md-3 -->
                <div class="col-md-9 col-sm-9 col-xs-12 header-inner-right">
                    <div class="row">
                        <!-- TODO: Перенести стиль в css-->
                        <div class="col-12 header-inner-right">
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
                                                <?php /** @var \common\modules\cart\CartService $cartService */ ?>
                                                <p style="display: <?= ($count) ? 'initial' : 'none' ?>;" class="shop-cart-checkout-btn">
                                                    <a href="<?=Url::toRoute('/order/default/index') ?>" class="btn btn-custom btn-block">Оформить</a>
                                                </p>
                                            </div><!-- End .dropdown-cart-action -->
                                        </div><!-- End .dropdown-cart -->
                                    </div><!-- End .btn-group -->
                                </div><!-- End .dropdown-cart-menu-container -->
                                <?php endif; ?>
        
                                <div class="header-box contact-infos pull-right">
                                    <ul>
                                        <?php if(!empty(\Yii::$app->params['contacts']['skype'])): ?>
                                            <li>
                                                <span class="header-box-icon header-box-icon-skype"></span>
                                                <?=\Yii::$app->params['contacts']['skype']?>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(!empty(\Yii::$app->params['contacts']['email'])): ?>
                                            <li>
                                                <span class="header-box-icon header-box-icon-email"></span>
                                                <a href="mailto:<?=\Yii::$app->params['contacts']['email']?>">
                                                    <?=\Yii::$app->params['contacts']['email']?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div><!-- End .contact-infos -->
        
                                <div class="header-box contact-phones pull-right clearfix">
                                    <span class="header-box-icon header-box-icon-earphones"></span>
                                    <ul class="pull-left">
                                        <li><?=\Yii::$app->params['contacts']['Телефоны'][0]?></li>
                                        <?php if(!empty(\Yii::$app->params['contacts']['Телефоны'][1])): ?>
                                            <li><?=\Yii::$app->params['contacts']['Телефоны'][1]?></li>
                                        <?php endif; ?>
                                    </ul>
                                </div><!-- End .contact-phones -->
        
                            </div><!-- End .header-inner-right-wrapper -->
                        </div><!-- End .col-md-7 -->
                        
                    </div><!-- End .row -->
                    <div class="row">
                        <?=\common\modules\search\widgets\SearchWidget::widget()?>
                    </div><!-- End .row -->
                </div><!-- End .col-md-9 -->
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
                                        <a class="active" href="<?= Url::to(['/site/index']); ?>">Главная страница</a>
                                    </li>

                                    <li class="mega-menu-container">
                                        <a href="<?=Url::toRoute('/catalog/default/index')?>">Каталог</a>
                                        <?= TopMenu::widget(); ?>
                                    </li>

                                    <li><a href="http://montage.8str.ru"><?= Yii::t('app.common', 'Монтаж');?></a></li>
                                    <li><a href="http://montage.8str.ru/#cases"><?= Yii::t('app.common', 'Наши работы'); ?></a></li>
                                    <li><a href="<?= Url::to(['/site/contacts']);?>"><?= Yii::t('app.common', 'Контакты'); ?></a></li>
                                    <li><a href="<?= Url::to(['/site/delivery-and-payments']);?>"><?= Yii::t('app.common', 'Доставка и оплата');?></a></li>
                                    <li><a href="<?= Url::to(['/news']); ?>"><?= Yii::t('app.common', 'Новости'); ?></a></li>
                                </ul>
                            </div>

                        </nav>

                    </div><!-- End .col-md-12 -->
            </div><!-- End .row -->
        </div><!-- End .container -->

        </div><!-- End #nav -->
    </div><!-- End #inner-header -->
</header><!-- End #header -->

