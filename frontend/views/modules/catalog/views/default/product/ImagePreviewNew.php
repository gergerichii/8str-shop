<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 26.05.2018
 * Time: 14:42
 */

/**
 * @var \yii\web\View $this
 * @var \common\modules\catalog\models\Product $product
 * @var \common\modules\catalog\Module $catalog
 */

$catalog = \Yii::$app->getModule('catalog');

/** @var \common\modules\files\Module $filesManager */
$filesManager = \Yii::$app->getModule('files');

$images = [];
foreach ($product->images as $image) {
    $images[] = [
        'url' => $filesManager->getFileUri('products/images', $image, true),
        'src' => $filesManager->getFileUri('products/images/medium', $image, true),
        'medium' => $filesManager->getFileUri('products/images', $image, true),
    ];
}
?>

<?php \common\helpers\ViewHelper::startRegisterCss($this) ?>
    <style>
        .elastislide-wrapper {
            box-shadow: unset !important;
        }
    </style>
<?php \common\helpers\ViewHelper::endRegisterCss() ?>

    <div id="product-image-carousel-container" style="">
        <?= common\widgets\ElastiSlide\ElastiSlideWidget::widget([
            'items' => $images,
            'options' => [
                'id' => 'product_thumbs'
            ],
            'clientOptions' => [
                'orientation' => \common\widgets\ElastiSlide\ElastiSlideWidget::ORIENTATION_VERTICAL,
                'minItems' => (count($images) > 4) ? 4 : count($images),
            ],
            'itemImageOptions' => [
//                'style' => [
//                    'width' => '92px',
//                    'height' => '139px',
//                ]
            ],
            'itemLinkOptions' => function($item) {
                return [
                    'data-image' => $item['medium'],
                    'data-zoom-image' => $item['url'],
                ];
            },
        ]);?>
    </div>
    <div id="product-image-container">
        <figure>
            <?= common\widgets\ElevateZoom\ElevateZoomWidget::widget([
                'items' => $images,
                'options' => [
                    'id' => 'product_image_zoom',
//                    'style' => [
//                        'width' => '404px',
//                        'height' => '566px',
//                    ],
                ],
                'clientOptions' => [
                    'gallery' => 'product_thumbs',
                    'zoomType' => "inner",
                ]
            ]); ?>
        
            <figcaption class="item-price-container">
                <?php if ($catalog->oldPriceOf($product, false)): ?>
                    <span class="old-price"><?= $catalog->oldPriceOf($product); ?></span>
                <?php endif; ?>
                <span class="item-price"><?= $catalog->priceOf($product); ?></span>
            </figcaption>
        </figure>
        
        <!--TODO: Сделать цикл по меткам -->
        <?php if ($catalog->productHasTag($product, 'new')): ?>
            <span class="new-rect">New</span>
        <?php endif; ?>

        <?php if ($catalog->discountOf($product)): ?>
            <span class="discount-rect"><?= $catalog->discountOf($product); ?></span>
        <?php endif; ?>
    </div>


<?php \common\helpers\ViewHelper::startRegisterScript($this) ?>
    <script>
        $('#product_image_zoom').bind('click', function (e) {
            alert();
        });
    </script>
<?php \common\helpers\ViewHelper::endRegisterScript() ?>
