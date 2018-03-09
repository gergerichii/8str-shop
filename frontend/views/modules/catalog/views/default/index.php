<?php

use common\modules\catalog\widgets\ProductFilterWidget;
use common\modules\catalog\widgets\ProductTagWidget;
use common\modules\catalog\widgets\ProductViewWidget;

/**
 * @var string $catalogPath
 * @var \yii\data\ActiveDataProvider $productsDataProvider
 * @var \common\modules\catalog\models\forms\ProductFilterForm $filterForm
 * @var $this yii\web\View
 */

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

                    <?= ProductFilterWidget::widget(['filterForm' => $filterForm]); ?>


                    <?= ProductTagWidget::widget([
                        'limit' => 12,
                        'tagName' => 'featured',
                        'viewName' => 'sidebarCatalog'
                    ]); ?>

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
