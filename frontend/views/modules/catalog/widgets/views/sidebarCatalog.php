<?php
/**
 * @var \common\modules\catalog\models\Product[] $products
 * @var \common\modules\catalog\Module $catalog
 */

use yii\helpers\Html;

$index = key($products);
?>

<div class="widget featured">
    <h3>Featured</h3>

    <div class="featured-slider flexslider sidebarslider">
        <ul class="featured-list clearfix">
            <?php while ($product = current($products)) { ?>
                <li>
                    <?php do { ?>
                        <?php
                        $encodedProductName = Html::encode($product);
                        $productUrl = $catalog->getCatalogUri(NULL, $product);
                        /** @var \common\modules\catalog\models\Product $product */
                        $mainImage = $product->mainImage;
                        ?>
                        <div class="featured-product clearfix">
                            <figure>
                                <img src="<?= $catalog->getProductThumbnailUri($mainImage, 'little'); ?>" alt="<?= $encodedProductName; ?>">
                            </figure>
                            <h5>
                                <a href="<?= $productUrl; ?>"><?= $encodedProductName; ?></a>
                            </h5>
                            <?php //TODO Add rating
                            /*<div class="ratings-container">
                                <div class="ratings">
                                    <div class="ratings-result" data-result="84"></div>
                                </div><!-- End .ratings -->
                            </div><!-- End .rating-container -->
                            */?>
                            <div class="featured-price"><?= $catalog->priceOf($product); ?></div><!-- End .featured-price -->
                        </div><!-- End .featured-product -->
                        <?php
                        next($products);
                        $index = key($products);
                        $product = current($products)
                        ?>
                    <?php } while ($product && $index % 4 != 0); ?>


                </li>
            <?php } ?>
        </ul>
    </div><!-- End .featured-slider -->
</div><!-- End .widget -->
