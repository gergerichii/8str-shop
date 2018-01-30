<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 11.01.2018
 * Time: 7:55
 */

/** @var \yii\web\View $this */

/** @var \common\models\entities\Product[][] $topModels*/

/**
 * TODO: Переделать. Не везде на сайте меню с такими настройками
 */
Yii::$container->setDefinitions([
    \yii\widgets\Menu::className() => [
        'options' => [
            'tag' => 'div',
            'class' => 'list-group list-group-brand list-group-accordion'
        ],
        'itemOptions' => ['tag' =>false],
        'linkTemplate' => '<a href="{url}" class="list-group-item">{label}</a>',
    ]
]);

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

                <?=$this->render('@app/views/pieces/sidebar')?>

                <div class="col-md-9 col-sm-8 col-xs-12 main-content ">
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
                                            $content .= $this->render('@app/views/pieces/productSmall', ['model' => $product], $this);
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
            onHoverStop:"true",
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
            shadow:0, // 1 2 3 to change shadows
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
