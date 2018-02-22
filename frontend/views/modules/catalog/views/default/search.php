<?php
/** @var \common\modules\catalog\providers\FrontendSearchProvider $provider */

use yii\widgets\ListView;

?>

<?= ListView::widget(['dataProvider' => $provider]); ?>
