<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>

<div class="site-index">
    <?=
    \common\modules\treeManager\TreeView::widget([
        'query' => \common\modules\catalog\models\ProductRubric::find()->addOrderBy('root, left_key'),
        'headingOptions' => ['label' => 'Categories'],
        'fontAwesome' => false,
        'isAdmin' => false,
        'displayValue' => 1,
        'softDelete' => true,
        'cacheSettings' => [
            'enableCache' => true
        ]
    ]);
    ?>
</div>
