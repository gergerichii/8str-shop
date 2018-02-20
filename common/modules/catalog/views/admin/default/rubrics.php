<?php

use common\modules\catalog\models\ProductRubric;
use common\modules\treeManager\Module;
use common\modules\treeManager\TreeView;
use yii\helpers\Html;

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
    'allowNewRoots' => false,
    'query' => ProductRubric::find()->addOrderBy('tree, left_key'),
    'headingOptions' => ['label' => 'Categories'],
    'fontAwesome' => false,
    'isAdmin' => Yii::$app->user->can('see_admin_settings_in_rubrics'),
    'displayValue' => 1,
    'softDelete' => true,
    'cacheSettings' => [
        'enableCache' => true
    ],
    'nodeAddlViews' => [
        Module::VIEW_PART_2 => '@common/modules/catalog/views/admin/default/_treeManagerForm'
    ],
    'nodeLabel' => function ($node) {
        /** @var ProductRubric $node */
        if (!$node->visible_on_home_page) {
            return Html::tag('del', $node->name, ['class' => 'text-muted']);
        }

        return $node->name;
    },
    'mainTemplate' => $mainTemplate,
]);