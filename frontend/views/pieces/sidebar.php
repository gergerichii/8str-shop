<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 10.01.2018
 * Time: 19:04
 */

use common\modules\catalog\widgets\ProductTagWidget;
use frontend\widgets\LeftMenu;
use yii\widgets\Menu;

/** @var \common\modules\catalog\Module $catalog */
$catalog = \Yii::$app->getModule('catalog');

?>
<div class="col-md-3 col-sm-4 col-xs-12 sidebar">
    <div class="widget">
        <h3>Категории</h3>

        <?= LeftMenu::widget([
            'options' => [
                'tag' => 'div',
                'class' => 'list-group list-group-brand list-group-accordion'
            ],
            'itemOptions' => ['tag' => false],
            'items' => $catalog->getMenuStructure(1),
            'linkTemplate' => '<a href="{url}" class="list-group-item">{label}<span class="filter-icon filter-icon-{icon}"></span></a>',
        ]); ?>
    </div>

    <div class="widget">
        <h3>Brands</h3>

        <?= Menu::widget([
            'options' => [
                'tag' => 'div',
                'class' => 'list-group list-group-brand'
            ],
            'itemOptions' => ['tag' => false],
            'linkTemplate' => '<a href="{url}" class="list-group-item">{label}</a>',
            'items' => $catalog->getBrandMenuStructure(),
        ]); ?>
    </div>

    <div class="widget subscribe">
        <h3>BE THE FIRST TO KNOW</h3>
        <p> Get all the latest information on Events, Sales and Offers. Sign up for the Venedor store newsletter today.</p>
        <form action="#" id="subscribe-form">
            <div class="form-group">
                <input type="email" class="form-control" id="subscribe-email" placeholder="Enter your email address">
            </div>
            <input type="submit" value="SUBMIT" class="btn btn-custom">
        </form>
    </div>

    <div class="widget testimonials">
        <h3>Testimonials</h3>

        <div class="testimonials-slider flexslider sidebarslider">
            <ul class="testimonials-list clearfix">
                <li>
                    <div class="testimonial-details">
                        <header>Best Service!</header>
                        Maecenas semper aliquam massa. Praesent pharetra sem vitae nisi eleifend molestie. Aliquam molestie scelerisque ultricies. Suspendisse potenti.
                    </div><!-- End .testimonial-details -->
                    <figure class="clearfix">
                        <img src="/images/testimonials/anna.jpg" alt="Computer Ceo">
                        <figcaption>
                            <a href="#">Anna Retallic</a>
                            <span>12.05.2013</span>
                        </figcaption>
                    </figure>
                </li>
                <li>
                    <div class="testimonial-details">
                        <header>Cool Style!</header>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt iure quisquam necessitatibus fugit! Nisi tempora reiciendis omnis error sapiente ipsam maiores dolorem maxime.
                    </div><!-- End .testimonial-details -->
                    <figure class="clearfix">
                        <img src="/images/testimonials/jake.jpg" alt="Computer Ceo">
                        <figcaption>
                            <a href="#">Jake Suasoo</a>
                            <span>17.05.2013</span>
                        </figcaption>
                    </figure>
                </li>
            </ul>
        </div><!-- End .testimonials-slider -->
    </div><!-- End .widget -->

    <div class="widget popular">
        <h3>Popular</h3>

        <div class="related-slider flexslider sidebarslider">
            <?= ProductTagWidget::widget([
                'limit' => 12,
                'tagName' => 'popular',
                'viewName' => '@frontend/views/modules/catalog/widgets/views/productTagWidget/sidebarPromo'
            ]); ?>
        </div><!-- End .related-slider -->
    </div>

    <div class="widget latest-posts">
        <h3>Recent Posts</h3>

        <div class="latest-posts-slider flexslider sidebarslider">
            <ul class="latest-posts-list clearfix">
                <li>
                    <a href="single.html">
                        <figure class="latest-posts-media-container">
                            <img class="img-responsive" src="/images/blog/post1-small.jpg" alt="lats post">
                        </figure>
                    </a>
                    <h4><a href="single.html">35% Discount on second purchase!</a></h4>
                    <p>Sed blandit nulla nec nunc ullamcorper tristique. Mauris adipiscing cursus ante ultricies dictum sed lobortis.</p>
                    <div class="latest-posts-meta-container clearfix">
                        <div class="pull-left">
                            <a href="#">Read More...</a>
                        </div><!-- End .pull-left -->
                        <div class="pull-right">
                            12.05.2013
                        </div><!-- End .pull-right -->
                    </div><!-- End .latest-posts-meta-container -->
                </li>

                <li>
                    <a href="single.html">
                        <figure class="latest-posts-media-container">
                            <img class="img-responsive" src="/images/blog/post2-small.jpg" alt="lats post">
                        </figure>
                    </a>
                    <h4><a href="single.html">Free shipping for regular customers.</a></h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque fuga officia in molestiae easint..</p>
                    <div class="latest-posts-meta-container clearfix">
                        <div class="pull-left">
                            <a href="#">Read More...</a>
                        </div><!-- End .pull-left -->
                        <div class="pull-right">
                            10.05.2013
                        </div><!-- End .pull-right -->
                    </div><!-- End .latest-posts-meta-container -->
                </li>

                <li>
                    <a href="single.html">
                        <figure class="latest-posts-media-container">
                            <img class="img-responsive" src="/images/blog/post3-small.jpg" alt="lats post">
                        </figure>
                    </a>
                    <h4><a href="#">New jeans on sales!</a></h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque fuga officia in molestiae easint..</p>
                    <div class="latest-posts-meta-container clearfix">
                        <div class="pull-left">
                            <a href="#">Read More...</a>
                        </div><!-- End .pull-left -->
                        <div class="pull-right">
                            8.05.2013
                        </div><!-- End .pull-right -->
                    </div><!-- End .latest-posts-meta-container -->
                </li>

            </ul>
        </div><!-- End .latest-posts-slider -->
    </div><!-- End .widget -->

    <div class="widget banner-slider-container">
        <div class="banner-slider flexslider">
            <ul class="banner-slider-list clearfix">
                <li><a href="#"><img src="/images/banner1.jpg" alt="Banner 1"></a></li>
                <li><a href="#"><img src="/images/banner2.jpg" alt="Banner 2"></a></li>
                <li><a href="#"><img src="/images/banner3.jpg" alt="Banner 3"></a></li>
            </ul>
        </div>
    </div><!-- End .widget -->

</div><!-- End .col-md-3 -->

