<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>

<div class="site-index">
    <?php
    $mainTemplate = <<< HTML
<div class="row">
    <div class="col-sm-5">
        {wrapper}
    </div>
    <div class="col-sm-7">
        {detail}
    </div>
</div>
HTML;

    echo \common\modules\treeManager\TreeView::widget([
        'query' => \common\modules\catalog\models\ProductRubric::find()->addOrderBy('root, left_key'),
        'headingOptions' => ['label' => 'Categories'],
        'fontAwesome' => false,
        'isAdmin' => false,
        'displayValue' => 1,
        'softDelete' => true,
        'cacheSettings' => [
            'enableCache' => true
        ],
        'mainTemplate' => $mainTemplate
    ]);
    ?>
</div>
