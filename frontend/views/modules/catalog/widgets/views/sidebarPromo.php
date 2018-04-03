<?php
/**
 * @var \common\modules\catalog\models\Product[] $products
 * @var \common\modules\catalog\Module $catalog
 */

use yii\helpers\Html;

$index = key($products);
?>

<ul class="related-list clearfix">
    <?php while ($product = current($products)) { ?>
        <li>
            <?php do { ?>
                <?php
                $encodedProductName = Html::encode((string)$product);
                $productUrl = $catalog->getCatalogUri(NULL, $product);
                /** @var \common\modules\catalog\models\Product $product */
                $mainImage = $product->mainImage;
                ?>

                <div class="related-product clearfix">
                    <figure>
                        <img src="<?= $catalog->getProductThumbnailUri($mainImage, 'little'); ?>" alt="<?= $encodedProductName; ?>">
                    </figure>

                    <h5><a href="<?= $productUrl; ?>"><?= $encodedProductName; ?></a></h5>

                    <?php
                    // TODO Add rating
                    /*<div class="ratings-container">
                        <div class="ratings">
                            <div class="ratings-result" data-result="84"></div>
                        </div><!-- End .ratings -->
                    </div><!-- End .rating-container -->*/
                    ?>

                    <div class="related-price"><?= $catalog->priceOf($product); ?></div><!-- End .related-price -->
                </div><!-- End .related-product -->

                <?php
                next($products);
                $index = key($products);
                $product = current($products)
                ?>
            <?php } while ( $product && $index % 4 != 0); ?>
        </li>
    <?php } ?>
</ul>

