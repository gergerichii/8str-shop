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


                        </div><!-- End .toolbox-filter -->
                        <div class="toolbox-pagination clearfix">
                            <?=$pager->run()?>

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


                </aside><!-- End .col-md-3 -->
            </div><!-- End .row -->


        </div><!-- End .col-md-12 -->
    </div><!-- End .row -->
</div><!-- End .container -->
