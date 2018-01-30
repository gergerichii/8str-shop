<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 11.01.2018
 * Time: 20:57
 */

/** @var \common\models\entities\Product $model */

$image1 = isset($model->images[0]) ? $model->images[0] : 'default.jpg';
$image2 = isset($model->images[1]) ? $model->images[1] : $image1;
$image1 = \common\modules\files\Module::getImageUri($image1);
$image2 = \common\modules\files\Module::getImageUri($image2);

/** @var \common\services\CatalogService $catalog */
$catalog = \Yii::$app->getModule('catalog');

try{
    $productUrl = $catalog->getCatalogUri(NULL, $model);
} catch(\yii\base\ErrorException $e){
    Yii::error($e->getMessage());
    $productUrl = '';
}

$coverItem = isset($coverItem) && $coverItem ? $coverItem : false;
?>
<?= ($coverItem) ? '<div class="col-md-4 col-sm-6 col-xs-12">' : ''?>
    <div class="item item-hover">
        <div class="item-image-wrapper">
            <figure class="item-image-container">
                <a href="<?=$productUrl?>">
                    <img src="<?=$image1?>"
                         alt="<?=(string) $model?>" class="item-image">
                    <img src="<?=$image2?>"
                         alt="<?=(string) $model?>" class="item-image-hover">
                </a>
            </figure>
            <div class="item-price-container">
                <?php if ($catalog->oldPriceOf($model, false)): ?>
                    <span class="old-price"><?= $catalog->oldPriceOf($model) ?></span>
                <?php endif; ?>
                <span class="item-price"><?= $catalog->priceOf($model) ?></span>
            </div><!-- End .item-price-container -->
            <!--TODO: Сделать цикл по меткам -->
            <?php if ($catalog->productHasTag($model, 'new')):?>
                <span class="new-rect">New</span>
            <?php endif; ?>
            <?php if ($catalog->discountOf($model)):?>
                <span class="discount-rect"><?= $catalog->discountOf($model) ?></span>
            <?php endif; ?>
        </div><!-- End .item-image-wrapper -->
        <div class="item-meta-container">
            <h3 class="item-name"><a href="<?= $productUrl ?>"><?= $model ?></a></h3>
            <div class="item-action">
                <?php \dvizh\cart\widgets\BuyButton::begin([
                        'model' => $model,
                ])?>
                    <span class="icon-cart-text">Add to Cart</span>
                <?php \dvizh\cart\widgets\BuyButton::end() ?>
                <a href="#" class="item-add-btn">
                    <span class="icon-cart-text">Add to Cart</span>
                </a>
                <div class="item-action-inner">
                    <a href="#" class="icon-button icon-like">Favourite</a>
                    <a href="#" class="icon-button icon-compare">Checkout</a>
                </div><!-- End .item-action-inner -->
            </div><!-- End .item-action -->
        </div><!-- End .item-meta-container -->
    </div><!-- End .item -->
<?= ($coverItem) ? '</div>' : ''?>

