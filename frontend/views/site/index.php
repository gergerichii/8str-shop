<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 11.01.2018
 * Time: 7:55
 */

use common\modules\catalog\widgets\ProductBrandsWidget;
use common\modules\catalog\widgets\ProductViewWidget;
use common\modules\news\widgets\LatestNewsWidgets;

/** @var \yii\web\View $this */

/** @var \common\modules\catalog\models\Product[][] $topModels*/

/**
 * TODO: Переделать. Не везде на сайте меню с такими настройками
 */
Yii::$container->setDefinitions([
    \yii\widgets\Menu::class => [
        'options' => [
            'tag' => 'div',
            'class' => 'list-group list-group-brand list-group-accordion'
        ],
        'itemOptions' => ['tag' =>false],
        'linkTemplate' => '<a href="{url}" class="list-group-item">{label}</a>',
    ]
]);

/** TODO: Должно быть в самих метках */
$tagLabels = [
        'new' => 'Новинки',
        'bestseller' => 'Хит продаж',
        'sale' => 'Распродажа',
        'promo' => 'Спецпредложение',
];
$activeTab = 'new';

/** --------------------------------------------Карусель с товарами------------------------------------------------- */
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row slider-position">
                <div class="md-margin"></div><!-- space -->

                <?= $this->render('@app/views/pieces/sidebar'); ?>

                <div class="col-md-9 col-sm-8 col-xs-12 main-content ">
                    <div id="slider-rev-container">
                        <div id="slider-rev">
                            <ul>
                                <li data-transition="random"  data-saveperformance="on"  data-title="Подароки нашим
                                клиентам">
                                    <img src="images/revslider/dummy.png"  alt="slidebg1" data-lazyload="images/homeslider/slide1.jpg" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat">
                                    <div class="tp-caption rev-title lft ltt" data-x="530" data-y="140" data-speed="1100" data-start="300" data-endspeed="350">
                                    </div>
                                    <div class="tp-caption rev-list lfr ltr" data-x="575" data-y="110"
                                         data-speed="1500" data-start="350"
                                    data-easing="Power3.easeIn" data-endspeed="150">
                                        <h2>Жесткий диск, кабель <br> или коммутатор <br>в подарок!</h2>
                                    </div>
                                    <div class="tp-caption lfr ltr" data-x="530" data-y="250" data-speed="1500" data-start="450"  data-endspeed="100" data-easing="Power3.easeIn">
                                        <img src="images/homeslider/bullet.png" alt="bullet">
                                    </div>
                                    <div class="tp-caption rev-list lfr ltr" data-x="575" data-y="246" data-speed="1500" data-start="550"
                                    data-easing="Power3.easeIn" data-endspeed="150">
                                    Easy to use &amp; customize
                                    </div>

                                    <div class="tp-caption lfr ltr" data-x="530" data-y="290" data-speed="1500" data-start="650"  data-endspeed="180" data-easing="Power3.easeIn">
                                        <img src="images/homeslider/bullet.png" alt="bullet">
                                    </div>

                                    <div class="tp-caption rev-list lfr ltr" data-x="575" data-y="286" data-speed="1500" data-start="800"
                                    data-easing="Power3.easeIn" data-endspeed="230">
                                    Responsive Layout
                                    </div>

                                    <div class="tp-caption lfr ltr" data-x="530" data-y="330" data-speed="1500" data-start="950"  data-endspeed="260" data-easing="Power3.easeIn">
                                        <img src="images/homeslider/bullet.png" alt="bullet">
                                    </div>

                                    <div class="tp-caption rev-list lfr ltr" data-x="575" data-y="326" data-speed="1500" data-start="1100"
                                    data-easing="Power3.easeIn" data-endspeed="290">
                                    Unlimited Color Skins
                                    </div>

                                    <div class="tp-caption lfr ltr" data-x="530" data-y="370" data-speed="1500" data-start="1200"  data-endspeed="320" data-easing="Power3.easeIn">
                                        <img src="images/homeslider/bullet.png" alt="bullet">
                                    </div>

                                    <div class="tp-caption rev-list lfr ltr" data-x="575" data-y="366" data-speed="1500" data-start="1350"
                                    data-easing="Power3.easeIn" data-endspeed="350">
                                    Retina Ready
                                    </div>
                                </li>

                                <li data-transition="random"  data-saveperformance="on"  data-title="The Next Big Thing">
                                    <img src="images/revslider/dummy.png"  alt="slidebg2" data-lazyload="images/homeslider/slide2.jpg" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat">

                                    <div class="tp-caption customin customout rev-price randomrotate randomrotateout" data-x="118" data-y="35" data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;" data-speed="800" data-start="1500" data-endspeed="400">
                                        $1150
                                    </div>

                                    <div class="tp-caption lfr ltr" data-x="-140" data-y="30" data-speed="1200" data-start="600" data-easing="Expo.easeOut">
                                        <a href="#"><img src="images/homeslider/slide2_2.png" alt="slide1_1"></a>
                                    </div>

                                    <div class="tp-caption lfl ltl" data-x="220" data-y="40" data-speed="1200" data-start="600" data-easing="Expo.easeOut">
                                        <a href="#"><img src="images/homeslider/slide2_1.png" alt="slide1_1"></a>
                                    </div>

                                    <div class="tp-caption rev-title2 skewfromleft stt" data-x="530" data-y="170" data-speed="800" data-start="900"
                                    data-easing="Power3.easeIn" data-endspeed="300">The next big Thing...</div>
                                    
                                    <div class="tp-caption rev-text sfl stl" data-x="530" data-y="215" data-speed="800" data-start="1300" data-easing="Power3.easeIn"
                                    data-endspeed="300">Take, view and share photos with<br> the 13MP camera and stunning <br> 5" display.</div>
                                    
                                    <div class="tp-caption sfb stb" data-x="530" data-y="315" data-speed="900" data-start="1600" data-easing="Power3.easeIn" data-endspeed="300">
                                        <a href="#" class="btn btn-sm btn-custom-2">Learn More</a>
                                    </div>
                                </li>

                                <li data-transition="random"  data-saveperformance="on"  data-title="Powerful Template">
                                    
                                    <img src="images/revslider/dummy.png"  alt="slidebg3" data-lazyload="images/homeslider/slide3.jpg" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat">
                                    <div class="tp-caption rev-title lfl ltl" data-x="40" data-y="140" data-speed="800" data-start="300" data-endspeed="350">Venedor Extremely <br>Powerful Template
                                    </div>

                                    <div class="tp-caption sfr stl" data-x="40" data-y="240" data-speed="1000" data-start="500"  data-endspeed="100">
                                        <img src="images/homeslider/bullet-reverse.png" alt="bullet">
                                    </div>

                                    <div class="tp-caption rev-list sfr stl" data-x="85" data-y="236" data-speed="1000" data-start="650"
                                    data-endspeed="150">
                                    9 Homepage Layout
                                    </div>

                                    <div class="tp-caption sfr stl" data-x="40" data-y="282" data-speed="1000" data-start="750"  data-endspeed="180">
                                        <img src="images/homeslider/bullet-reverse.png" alt="bullet">
                                    </div>

                                    <div class="tp-caption rev-list sfr stl" data-x="85" data-y="278" data-speed="1000" data-start="900"
                                    data-endspeed="230">
                                    Revolution Slider
                                    </div>

                                    <div class="tp-caption sfr stl" data-x="40" data-y="324" data-speed="1000" data-start="1000"  data-endspeed="260">
                                        <img src="images/homeslider/bullet-reverse.png" alt="bullet">
                                    </div>

                                    <div class="tp-caption rev-list sfr stl" data-x="85" data-y="320" data-speed="1000" data-start="1150"
                                    data-endspeed="290">
                                    Isotope Plugin
                                    </div>

                                    <div class="tp-caption sfr stl" data-x="40" data-y="366" data-speed="1000" data-start="1250"  data-endspeed="320">
                                        <img src="images/homeslider/bullet-reverse.png" alt="bullet">
                                    </div>

                                    <div class="tp-caption rev-list sfr stl" data-x="85" data-y="362" data-speed="1000" data-start="1400"
                                    data-endspeed="350">
                                    Unlimited Colors &amp; Fonts
                                    </div>
                                </li>
                                
                            </ul>
                        </div><!-- End #slider-rev -->
                    </div><!-- End #slider-rev-container -->
                    
                    <div class="md-margin"></div><!-- Space -->
                    
                    <div class="main-tab-container carousel-wrapper">
                        <ul id="products-tabs-list" class="tab-style-2 clearfix">

                            <?php foreach($topModels as $tag => $products): ?>
                                <?php if (!count($products)) continue; ?>
                                <li class="<?=($tag == $activeTab) ? 'active' : ''?>">
                                    <a href="#<?=$tag?>" data-toggle="tab"><?=$tagLabels[$tag]?></a>
                                </li>
                            <?php endforeach; ?>

                        </ul>
                        <div id="products-tabs-content" class="tab-content row">

                            <?php foreach($topModels as $tag => $products): ?>
                                <?php if (!count($products)) continue; ?>

                                <div class="tab-pane <?=($tag == $activeTab) ? 'active' : ''?> tab-carousel-wrapper" id="<?=$tag?>">

                                    <div class="carousel-controls">
                                        <div id="<?=$tag?>-tab-slider-prev" class="carousel-btn carousel-btn-prev"></div><!-- End .carousel-prev -->
                                        <div id="<?=$tag?>-tab-slider-next" class="carousel-btn carousel-btn-next carousel-space"></div><!-- End .carousel-next -->
                                    </div><!-- End .carousel-controllers -->

                                    <div class="<?=$tag?>-tab-slider top-tab-slider owl-carousel">

                                        <?php
                                        $i = 0;
                                        $content = '';
                                        $count = count($products);
                                        foreach($products as $index => $product) {
                                            $content .= ProductViewWidget::widget([
                                                'model' => $product
                                            ]);
//                                            $content .= $this->render('/pieces/productSmall', ['model' => $product], $this);
                                            $i ++;
                                            if ($i == 2 || $index + 1 === $count) {
                                                echo \yii\helpers\Html::tag('div', $content, ['class' => 'owl-single-col']);
                                                $content = '';
                                                $i = 0;
                                            }
                                        }
                                        ?>

                                    </div><!-- End .latest-tab-slider -->

                                </div><!-- End .tab-pane -->

                            <?php endforeach; ?>

                        </div><!-- End #products-tabs-content -->
                    </div><!-- End .main-tab-container -->

<!--                    <div class="md-margin2x"></div><!-- space -->

                    <?//= ProductBrandsWidget::widget(); ?>

<!--                    <div class="md-margin2x"></div><!-- space -->

                    <?= LatestNewsWidgets::widget(); ?>
                </div><!-- End .col-md-9 -->
            </div><!-- End .row -->
        </div><!-- End .col-md-12 -->
    </div><!-- End .row -->
</div><!-- End .container -->


<?php \common\helpers\ViewHelper::startRegisterScript($this)?>
<script>
    $(function() {

        // Slider Revolution
        jQuery('#slider-rev').revolution({
            delay:5000,
            startwidth:870,
            startheight:520,
            onHoverStop:"on",
            hideThumbs:250,
            navigationHAlign:"center",
            navigationVAlign:"bottom",
            navigationHOffset:0,
            navigationVOffset:15,
            soloArrowLeftHalign:"left",
            soloArrowLeftValign:"center",
            soloArrowLeftHOffset:0,
            soloArrowLeftVOffset:0,
            soloArrowRightHalign:"right",
            soloArrowRightValign:"center",
            soloArrowRightHOffset:0,
            soloArrowRightVOffset:0,
            touchenabled:"on",
            stopAtSlide:-1,
            stopAfterLoops:-1,
            dottedOverlay:"none",
            fullWidth:"on",
            spinned:"spinner4",
            shadow:3, // 1 2 3 to change shadows
            hideTimerBar: "on",
            // navigationStyle:"preview2"
        });

        /* This is fix for mobile devices position slider at the top  via absolute pos */
        var fixSliderForMobile = function () {
            var winWidth = $(window).width();

            if (winWidth <= 767 && $('#slider-rev-container').length) {
                var revSliderHeight = $('#slider-rev').height();
                console.log(revSliderHeight);
                $('.slider-position').css('padding-top', revSliderHeight);
                $('.main-content').css('position', 'static');
            } else {
                $('.slider-position').css('padding-top', 0);
                $('.main-content').css('position', 'relative');
            }
        };

        fixSliderForMobile();

        /* Resize fix positionin */
        if($.event.special.debouncedresize) {
            $(window).on('debouncedresize', function() {
                fixSliderForMobile();
            });
        } else {
            $(window).on('resize', function () {
                fixSliderForMobile();
            });
        }


    });
</script>
<?php \common\helpers\ViewHelper::endRegisterScript()?>
