<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 27.12.2017
 * Time: 17:07
 */

use common\modules\catalog\widgets\ProductTagWidget;

?>

<footer id="footer">
    <div id="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12 widget">
                    <div class="title-bg">
                        <h3>Популярное</h3>
                    </div><!-- End .title-bg -->

                    <div class="footer-popular-slider flexslider footerslider">
                        <?= ProductTagWidget::widget(['tagName' => 'popular']); ?>
                    </div><!-- End Footerpouplar-slider -->
                    <div class="md-margin visible-xs"></div><!-- space -->
                </div><!-- End .col-md-4 -->

                <div class="col-md-4 col-sm-4 col-xs-12 widget">
                    <div class="title-bg">
                        <h3>Рекомендуемое</h3>
                    </div><!-- End .title-bg -->

                    <div class="footer-featured-slider flexslider footerslider">
                        <?= ProductTagWidget::widget(['tagName' => 'featured']); ?>
                    </div><!-- End Footerpouplar-slider -->
                    <div class="md-margin visible-xs"></div><!-- space -->
                </div><!-- End .col-md-4 -->

                <div class="col-md-4 col-sm-4 col-xs-12 widget">
                    <div class="title-bg">
                        <h3>Спецпредложения</h3>
                    </div><!-- End .title-bg -->

                    <div class="footer-specials-slider flexslider footerslider">
                        <?= ProductTagWidget::widget(['tagName' => 'promo']); ?>
                    </div><!-- End Footerpouplar-slider -->

                </div><!-- End .col-md-4 -->
            </div><!-- End .row -->
        </div><!-- End .container -->
    </div><!-- End #footer-top -->

    <div id="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-sm-7 col-xs-12 footer-social-links-container">
                    <ul class="social-links clearfix">
                    </ul>
                </div><!-- End .col-md-7 -->

                <div class="col-md-5 col-sm-5 col-xs-12 footer-text-container">
                    <p>&copy; 2018 Восьмой страж. Все права защищены.</p>
                </div><!-- End .col-md-5 -->
            </div><!-- End .row -->
        </div><!-- End .container -->
    </div><!-- End #footer-bottom -->

</footer><!-- End #footer -->
