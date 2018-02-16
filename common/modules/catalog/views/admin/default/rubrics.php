<?php

use common\modules\catalog\models\ProductRubric;
use common\modules\treeManager\Module;
use common\modules\treeManager\TreeView;

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

echo TreeView::widget([
    'query' => ProductRubric::find()->addOrderBy('tree, left_key'),
    'headingOptions' => ['label' => 'Categories'],
    'fontAwesome' => false,
    'isAdmin' => false,
    'displayValue' => 1,
    'softDelete' => true,
    'cacheSettings' => [
        'enableCache' => true
    ],
    'nodeAddlViews' => [
        Module::VIEW_PART_2 => '@common/modules/catalog/views/admin/default/_treeManagerForm'
    ],
    'mainTemplate' => $mainTemplate
]);