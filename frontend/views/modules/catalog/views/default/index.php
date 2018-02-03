<?php
/* @var $this yii\web\View */

use common\modules\catalog\widgets\ProductViewWidget;

/* @var string $catalogPath */
/* @var \yii\data\ActiveDataProvider $productsDataProvider */

$productsDataProvider->pagination->pageSize = 12;
$productsDataProvider->prepare();
$pagination = $productsDataProvider->getPagination();
$pager = new \yii\widgets\LinkPager([
    'pagination' => $pagination,
    'maxButtonCount' => 5,
]);
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="row">

                <div class="col-md-9 col-sm-8 col-xs-12 main-content">

                    <div class="category-toolbar clearfix">
                        <div class="toolbox-filter clearfix">

                            <div class="sort-box">
                                <span class="separator">sort by:</span>
                                <div class="btn-group select-dropdown">
                                    <button type="button" class="btn select-btn">Position</button>
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="#">Date</a></li>
                                        <li><a href="#">Name</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="view-box">
                                <a href="category.html" class="active icon-button icon-grid"><i class="fa fa-th-large"></i></a>
                                <a href="category-list.html" class="icon-button icon-list"><i class="fa fa-th-list"></i></a>
                            </div><!-- End .view-box -->

                        </div><!-- End .toolbox-filter -->
                        <div class="toolbox-pagination clearfix">
                            <?=$pager->run()?>
                            <div class="view-count-box">
                                <span class="separator">view:</span>
                                <div class="btn-group select-dropdown">
                                    <button type="button" class="btn select-btn">10</button>
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="#">15</a></li>
                                        <li><a href="#">30</a></li>
                                    </ul>
                                </div>
                            </div><!-- End .view-count-box -->

                        </div><!-- End .toolbox-pagination -->


                    </div><!-- End .category-toolbar -->
                    <div class="md-margin"></div><!-- .space -->
                    <div class="category-item-container">
                        <?= \yii\widgets\ListView::widget([
                            'dataProvider' => $productsDataProvider,
                            'itemView' => function ($model, $key, $index, $widget){
                                return ProductViewWidget::widget([
                                    'model' => $model,
                                    'viewParams' => $widget->viewParams
                                ]);
                            },
                            'layout' => '{items}',
                            'viewParams' => [
                                'coverItem' => true,
                            ],
                            'options' => ['class' => 'row']
                        ]); ?><!-- End .row -->
                    </div><!-- End .category-item-container -->

                    <div class="pagination-container clearfix">
                        <div class="pull-right">
                            <?=$pager->run()?>
                        </div><!-- End .pull-right -->

                        <div class="pull-right view-count-box hidden-xs">
                            <span class="separator">view:</span>
                            <div class="btn-group select-dropdown">
                                <button type="button" class="btn select-btn">10</button>
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-angle-down"></i>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">15</a></li>
                                    <li><a href="#">30</a></li>
                                </ul>
                            </div>
                        </div>
                    </div><!-- End pagination-container -->


                </div><!-- End .col-md-9 -->

                <aside class="col-md-3 col-sm-4 col-xs-12 sidebar">
                    <div class="widget">
                        <div class="panel-group custom-accordion sm-accordion" id="category-filter">
                            <div class="panel">
                                <div class="accordion-header">
                                    <div class="accordion-title"><span>Category</span></div><!-- End .accordion-title -->
                                    <a class="accordion-btn opened"  data-toggle="collapse" data-target="#category-list-1"></a>
                                </div><!-- End .accordion-header -->

                                <div id="category-list-1" class="collapse in">
                                    <div class="panel-body">
                                        <ul class="category-filter-list jscrollpane">
                                            <li><a href="#">Mobile Phones (341)</a></li>
                                            <li><a href="#">Smartphones (55)</a></li>
                                            <li><a href="#">Communicators (24)</a></li>
                                            <li><a href="#">CDMA Phones (14)</a></li>
                                            <li><a href="#">Accessories (83)</a></li>
                                            <li><a href="#">Chargers (8)</a></li>
                                            <li><a href="#">Memory Cards (6)</a></li>
                                            <li><a href="#">Protectors (12)</a></li>
                                            <li><a href="#">ravelsim (5)</a></li>
                                            <li><a href="#">CDMA Phones (14)</a></li>
                                            <li><a href="#">Accessories (83)</a></li>
                                            <li><a href="#">Chargers (8)</a></li>
                                            <li><a href="#">Memory Cards (6)</a></li>
                                            <li><a href="#">Protectors (12)</a></li>
                                            <li><a href="#">ravelsim (5)</a></li>
                                            <li><a href="#">CDMA Phones (14)</a></li>
                                            <li><a href="#">Accessories (83)</a></li>
                                            <li><a href="#">Chargers (8)</a></li>
                                            <li><a href="#">Memory Cards (6)</a></li>
                                            <li><a href="#">Protectors (12)</a></li>
                                            <li><a href="#">ravelsim (5)</a></li>
                                        </ul>
                                    </div><!-- End .panel-body -->
                                </div><!-- #collapse -->
                            </div><!-- End .panel -->

                            <div class="panel">
                                <div class="accordion-header">
                                    <div class="accordion-title"><span>Brand</span></div><!-- End .accordion-title -->
                                    <a class="accordion-btn opened"  data-toggle="collapse" data-target="#category-list-2"></a>
                                </div><!-- End .accordion-header -->

                                <div id="category-list-2" class="collapse in">
                                    <div class="panel-body">
                                        <ul class="category-filter-list jscrollpane">
                                            <li><a href="#">Samsung (50)</a></li>
                                            <li><a href="#">Apple (80)</a></li>
                                            <li><a href="#">HTC (20)</a></li>
                                            <li><a href="#">Motoroloa (20)</a></li>
                                            <li><a href="#">Nokia (11)</a></li>
                                        </ul>
                                    </div><!-- End .panel-body -->
                                </div><!-- #collapse -->
                            </div><!-- End .panel -->

                            <div class="panel">
                                <div class="accordion-header">
                                    <div class="accordion-title"><span>Price</span></div><!-- End .accordion-title -->
                                    <a class="accordion-btn opened"  data-toggle="collapse" data-target="#category-list-3"></a>
                                </div><!-- End .accordion-header -->

                                <div id="category-list-3" class="collapse in">
                                    <div class="panel-body">
                                        <div id="price-range">

                                        </div><!-- End #price-range -->
                                        <div id="price-range-details">
                                            <span class="sm-separator">from</span>
                                            <input type="text" id="price-range-low" class="separator">
                                            <span class="sm-separator">to</span>
                                            <input type="text" id="price-range-high">
                                        </div>
                                        <div id="price-range-btns">
                                            <a href="#" class="btn btn-custom-2 btn-sm">Ok</a>
                                            <a href="#" class="btn btn-custom-2 btn-sm">Clear</a>
                                        </div>

                                    </div><!-- End .panel-body -->
                                </div><!-- #collapse -->
                            </div><!-- End .panel -->
                        </div><!-- .panel-group -->
                    </div><!-- End .widget -->

                    <div class="widget featured">
                        <h3>Featured</h3>

                        <div class="featured-slider flexslider sidebarslider">
                            <ul class="featured-list clearfix">
                                <li>
                                    <div class="featured-product clearfix">
                                        <figure>
                                            <img src="/images/products/thumbnails/item5.jpg" alt="item5">
                                        </figure>
                                        <h5><a href="#">Jacket Suiting Blazer</a></h5>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="84"></div>
                                            </div><!-- End .ratings -->
                                        </div><!-- End .rating-container -->
                                        <div class="featured-price">$40</div><!-- End .featured-price -->
                                    </div><!-- End .featured-product -->

                                    <div class="featured-product clearfix">
                                        <figure>
                                            <img src="/images/products/thumbnails/item1.jpg" alt="item1">
                                        </figure>
                                        <h5><a href="#">Gap Graphic Cuffed</a></h5>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="84"></div>
                                            </div><!-- End .ratings -->
                                        </div><!-- End .rating-container -->
                                        <div class="featured-price">$18</div><!-- End .featured-price -->
                                    </div><!-- End .featured-product -->

                                    <div class="featured-product clearfix">
                                        <figure>
                                            <img src="/images/products/thumbnails/item2.jpg" alt="item2">
                                        </figure>
                                        <h5><a href="#">Women's Lauren Dress</a></h5>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="84"></div>
                                            </div><!-- End .ratings -->
                                        </div><!-- End .rating-container -->
                                        <div class="featured-price">$30</div><!-- End .featured-price -->
                                    </div><!-- End .featured-product -->
                                </li>
                                <li>
                                    <div class="featured-product clearfix">
                                        <figure>
                                            <img src="/images/products/thumbnails/item3.jpg" alt="item3">
                                        </figure>
                                        <h5><a href="#">Swiss Mobile Phone</a></h5>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="64"></div>
                                            </div><!-- End .ratings -->
                                        </div><!-- End .rating-container -->
                                        <div class="featured-price">$39</div><!-- End .featured-price -->
                                    </div><!-- End .featured-product -->

                                    <div class="featured-product clearfix">
                                        <figure>
                                            <img src="/images/products/thumbnails/item4.jpg" alt="item4">
                                        </figure>
                                        <h5><a href="#">Zwinzed HeadPhones</a></h5>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="94"></div>
                                            </div><!-- End .ratings -->
                                        </div><!-- End .rating-container -->
                                        <div class="featured-price">$18.99</div><!-- End .featured-price -->
                                    </div><!-- End .featured-product -->

                                    <div class="featured-product clearfix">
                                        <figure>
                                            <img src="/images/products/thumbnails/item7.jpg" alt="item7">
                                        </figure>
                                        <h5><a href="#">Kless Man Suit</a></h5>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="74"></div>
                                            </div><!-- End .ratings -->
                                        </div><!-- End .rating-container -->
                                        <div class="featured-price">$99</div><!-- End .featured-price -->
                                    </div><!-- End .featured-product -->
                                </li>
                                <li>

                                    <div class="featured-product clearfix">
                                        <figure>
                                            <img src="/images/products/thumbnails/item4.jpg" alt="item4">
                                        </figure>
                                        <h5><a href="#">Gap Graphic Cuffed</a></h5>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="84"></div>
                                            </div><!-- End .ratings -->
                                        </div><!-- End .rating-container -->
                                        <div class="featured-price">$17</div><!-- End .featured-price -->
                                    </div><!-- End .featured-product -->

                                    <div class="featured-product clearfix">
                                        <figure>
                                            <img src="/images/products/thumbnails/item6.jpg" alt="item6">
                                        </figure>
                                        <h5><a href="#">Women's Lauren Dress</a></h5>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-result" data-result="84"></div>
                                            </div><!-- End .ratings -->
                                        </div><!-- End .rating-container -->
                                        <div class="featured-price">$30</div><!-- End .featured-price -->
                                    </div><!-- End .featured-product -->
                                </li>
                            </ul>
                        </div><!-- End .featured-slider -->
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

                </aside><!-- End .col-md-3 -->
            </div><!-- End .row -->


        </div><!-- End .col-md-12 -->
    </div><!-- End .row -->
</div><!-- End .container -->
