<?php
use yii\helpers\Html;
use common\modules\cart\widgets\ChangeCount;
use common\modules\cart\widgets\DeleteButton;
use common\modules\cart\widgets\ElementPrice;
use common\modules\cart\widgets\ElementCost;


$image = isset($product->images[0]) ? $product->images[0] : 'default.jpg';
$image = \common\modules\files\Module::getImageUri($image);

/** @var \common\modules\catalog\Module $catalog */
$catalog = \Yii::$app->getModule('catalog');

try{
    $productUrl = $catalog->getCatalogUri(NULL, $product);
} catch(ErrorException $e){
    Yii::error($e->getMessage());
    $productUrl = '';
}

?>
<li class="item clearfix">
    <a href="#" title="Delete item" class="delete-item"><i class="fa fa-times"></i></a>
    <a href="#" title="Edit item" class="edit-item"><i class="fa fa-pencil"></i></a>
    <figure>
        <a href="<?= $productUrl ?>"><img src="<?= $image ?>" alt="<?= (string) $product ?>"></a>
    </figure>
    <div class="dropdown-cart-details">
        <p class="item-name">
        <a href="<?= $productUrl ?>"><?= $name ?></a>
        </p>
        <p>
            <?= ChangeCount::widget([
                            'model' => $model,
                            'showArrows' => $showCountArrows,
                            'actionUpdateUrl' => $controllerActions['update'],
                        ]); ?>
            <span class="item-price"><?= ElementPrice::widget(['model' => $model]); ?></span>
        </p>
    </div><!-- End .dropdown-cart-details -->
</li>
<?php return; ?>
<li class="dvizh-cart-row ">
    <div class=" row">
        <div class="col-xs-8">
            <?= $name ?>

            <?php if ($options) {
                $productOptions = '';
                foreach ($options as $optionId => $valueId) {
                    if ($optionData = $allOptions[$optionId]) {
                        $option = $optionData['name'];
                        $value = $optionData['variants'][$valueId];
                        $productOptions .= Html::tag('div', Html::tag('strong', $option) . ':' . $value);
                    }
                }
                echo Html::tag('div', $productOptions, ['class' => 'dvizh-cart-show-options']);
            } ?>

            <?php if(!empty($otherFields)) {
                foreach($otherFields as $fieldName => $field) {
                    if(isset($product->$field)) echo Html::tag('p', Html::tag('small', $fieldName.': '.$product->$field));
                }
            } ?>
        </div>
        <div class="col-xs-3">
            <?= ElementPrice::widget(['model' => $model]); ?>

            <?= ChangeCount::widget([
                'model' => $model,
                'showArrows' => $showCountArrows,
                'actionUpdateUrl' => $controllerActions['update'],
            ]); ?>

        </div>

        <?= Html::tag('div', DeleteButton::widget([
            'model' => $model,
            'deleteElementUrl' => $controllerActions['delete'],
            'lineSelector' => 'dvizh-cart-row ',
            'cssClass' => 'delete']),
            ['class' => 'shop-cart-delete col-xs-1']);
        ?>
    </div>
</li>
