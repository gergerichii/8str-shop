<?php
/**
 * @var \common\modules\catalog\models\ProductRubric[] $rubrics
 * @var \common\modules\catalog\models\ProductBrand[] $brands
 * @var \common\modules\catalog\Module $catalogModule
 * @var \common\modules\catalog\models\forms\ProductFilterForm $filterForm
 * @var float $priceRangeMin
 * @var float $priceRangeMax
 * @var float $priceStartMin
 * @var float $priceStartMax
 */

use yii\helpers\Html;

$count = 0;

?>

<div class="widget">
    <div class="panel-group custom-accordion sm-accordion" id="category-filter">
        <?php if ($rubrics) { ?>
            <div class="panel">
                <div class="accordion-header">
                    <div class="accordion-title">
                        <span>Category</span>
                    </div><!-- End .accordion-title -->
                    <a class="accordion-btn opened" data-toggle="collapse" data-target="#category-list-1"></a>
                </div><!-- End .accordion-header -->

                <div id="category-list-1" class="collapse in">
                    <div class="panel-body">
                        <ul class="category-filter-list jscrollpane">
                            <?php foreach ($rubrics as $rubric) { ?>
                                <li>
                                    <a href="<?= $filterForm->makeRubricUri($rubric); ?>"><?= Html::encode($rubric->name) . ' (' . $rubric->product_quantity . ')'; ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div><!-- End .panel-body -->
                </div><!-- #collapse -->
            </div><!-- End .panel -->
        <?php } ?>

        <?php if ($brands) { ?>
            <div class="panel">
                <div class="accordion-header">
                    <div class="accordion-title">
                        <span>Brand</span>
                    </div><!-- End .accordion-title -->
                    <a class="accordion-btn opened" data-toggle="collapse" data-target="#category-list-2"></a>
                </div><!-- End .accordion-header -->

                <div id="category-list-2" class="collapse in">
                    <div class="panel-body">
                        <ul class="category-filter-list jscrollpane">
                            <?php foreach ($brands as $brand) { ?>
                                <li>
                                    <a href="<?= $filterForm->makeBrandUri($brand); ?>"><?= Html::encode($brand->name) . ' (' . $brand->product_quantity . ')'; ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div><!-- End .panel-body -->
                </div><!-- #collapse -->
            </div><!-- End .panel -->
        <?php } ?>

        <div class="panel">
            <div class="accordion-header">
                <div class="accordion-title">
                    <span>Price</span>
                </div><!-- End .accordion-title -->
                <a class="accordion-btn opened" data-toggle="collapse" data-target="#category-list-3"></a>
            </div><!-- End .accordion-header -->

            <div id="category-list-3" class="collapse in">
                <div class="panel-body">
                    <form>
                        <div id="price-range" data-range-min="<?= $priceRangeMin; ?>" data-range-max="<?= $priceRangeMax; ?>" data-start-min="<?= $priceStartMin; ?>" data-start-max="<?= $priceStartMax; ?>">

                        </div><!-- End #price-range -->
                        <div id="price-range-details">
                            <span class="sm-separator">from</span>
                            <input name="from" type="text" value="<?= $filterForm->from; ?>" id="price-range-low" class="separator">
                            <span class="sm-separator">to</span>
                            <input name="to" type="text" value="<?= $filterForm->to; ?>" id="price-range-high">
                        </div>

                        <div id="price-range-btns">
                            <button type="submit" class="btn btn-custom-2 btn-sm">Ok</button>
                            <a href="#" class="btn btn-custom-2 btn-sm">Clear</a>
                        </div>
                    </form>
                </div><!-- End .panel-body -->
            </div><!-- #collapse -->
        </div><!-- End .panel -->
    </div><!-- .panel-group -->
</div><!-- End .widget -->