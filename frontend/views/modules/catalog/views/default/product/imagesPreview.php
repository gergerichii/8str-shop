<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 13.01.2018
 * Time: 22:54
 */

/**
 * @var \yii\web\View $this
 * @var \common\modules\catalog\models\Product $product
 * @var \common\modules\catalog\Module $catalog
 */
$catalog = \Yii::$app->getModule('catalog');

/** @var \common\modules\files\Module $filesManager */
$filesManager = \Yii::$app->getModule('files');
?>

<div class="col-md-6 col-sm-12 col-xs-12 product-viewer clearfix">

    <div id="product-image-carousel-container">
        <ul id="product-carousel" class="celastislide-list">

            <!-- TODO: Сделать ссылки на разные картинки, ака тумбы, биг и т.д.-->
            <?php $active = 'class="active-slide"'; ?>
            <?php foreach ($product->images as $image): ?>
                <?php $image = $filesManager->getFileUri('products/images', $image); ?>
                <li <?=$active?>>
                    <a data-rel='prettyPhoto[product]' href="<?=$image?>"
                       data-image="<?=$image?>" data-zoom-image="<?=$image?>"
                       class="product-gallery-item">
                        <img src="<?=$image?>" alt="<?=$product?>">
                    </a>
                </li>
                <?php $active = '' ?>
            <?php endforeach; ?>

        </ul><!-- End product-carousel -->
    </div>

    <div id="product-image-container">
        <?php
        // TODO Rewrite to get default image
        $image = null;
        if ($product->images && array_key_exists(0, $product->images)) {
            $image = $filesManager->getFileUri('products/images', $product->images[0]);
        }
        ?>
        <figure><img src="<?=$image?>" data-zoom-image="<?=$image?>" alt="<?=$product?>" id="product-image">
            <figcaption class="item-price-container">
                <?php if ($catalog->oldPriceOf($product, false)): ?>
                    <span class="old-price"><?= $catalog->oldPriceOf($product) ?></span>
                <?php endif; ?>
                <span class="item-price"><?= $catalog->priceOf($product) ?></span>
            </figcaption>
        </figure>
        <!--TODO: Сделать цикл по меткам -->
        <?php if ($catalog->productHasTag($product, 'new')):?>
            <span class="new-rect">New</span>
        <?php endif; ?>
        <?php if ($catalog->discountOf($product)):?>
            <span class="discount-rect"><?= $catalog->discountOf($product) ?></span>
        <?php endif; ?>
    </div><!-- product-image-container -->

</div><!-- End .col-md-6 -->

