<?php

use common\modules\cart\widgets\BuyButton;
use yii\helpers\Html;

/**
 * @var \common\modules\catalog\models\Product $model
 * @var string $pictureSrc
 * @var float $price
 */
?>

<img class="picture" src="<?= $pictureSrc; ?>">
<span class="name"><?= Html::encode($model->name); ?></span>
<span class="price"><?= Html::encode($price); ?></span>
<?php BuyButton::begin([
    'htmlTag' => 'span',
    'model' => $model,
    'cssClass' => 'item-add-btn',
]) ?>
<span class="button">В корзину</span>
<?php BuyButton::end() ?>
