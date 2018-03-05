<?php
/**
 * @var \common\modules\catalog\models\Product[] $products
 * @var \common\modules\catalog\Module $catalog
 */

use yii\helpers\Html;

$index = key($products);
?>

<ul class="slides">
    <?php while ($product = current($products)) { ?>
        <li>
            <?php do { ?>
                <?php
                $encodedProductName = Html::encode($product);
                $productUrl = $catalog->getCatalogUri(NULL, $product);
                /** @var \common\modules\catalog\models\Product $product */
                $mainImage = $product->mainImage;
                ?>

                <div class="slide-item clearfix">
                    <figure class="item-image-container">
                        <a href="<?= $productUrl; ?>"><img src="<?= $catalog->getProductThumbnailUri($mainImage, 'little'); ?>" alt="<?= $encodedProductName; ?>"></a>
                    </figure>

                    <p class="item-name">
                        <a href="<?= $productUrl; ?>"><?= $encodedProductName; ?></a>
                    </p>

                    <?php
                    // TODO Add rating
                    /*<div class="ratings-container">
                        <div class="ratings">
                            <div class="ratings-result" data-result="80"></div>
                        </div>
                    </div>*/
                    ?>

                    <div class="item-price-special">
                        <?= $catalog->priceOf($product); ?>
                    </div>
                </div>

                <?php
                next($products);
                $index = key($products);
                $product = current($products)
                ?>
            <?php } while ( $product && $index % 3 != 0); ?>
        </li>
    <?php } ?>
</ul>
