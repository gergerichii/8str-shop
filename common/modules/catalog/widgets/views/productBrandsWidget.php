<?php
/**
 * @var \common\modules\catalog\models\ProductBrand[] $brands
 * @var \common\modules\catalog\CatalogModule         $catalogModule
 * @var \common\modules\files\FilesModule             $filesModule
 */
?>

<div id="brand-slider-container" class="carousel-wrapper">
    <header class="content-title">
        <div class="title-bg">
            <h2 class="title">Manufacturers</h2>
        </div>
    </header>

    <div class="carousel-controls">
        <div id="brand-slider-prev" class="carousel-btn carousel-btn-prev">
        </div>
        <div id="brand-slider-next" class="carousel-btn carousel-btn-next carousel-space">
        </div>
    </div>

    <div class="sm-margin"></div>

    <div class="row">
        <div class="brand-slider owl-carousel">
            <?php foreach ($brands as $brand) { ?>
                <a href="<?= $catalogModule->getBrandUri($brand); ?>">
                    <img src="<?= $catalogModule->getBrandThumbnailUri($brand->logo, 'little'); ?>" alt="<?= $brand->name; ?>">
                </a>
            <?php } ?>
        </div>
    </div>
</div>
