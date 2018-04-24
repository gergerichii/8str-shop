<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 24.04.2018
 * Time: 17:47
 */

$orderFilters = [
    'default' => 'По умолчанию',
    'price' => 'По цене',
    'name' => 'По имени',
];

$orderSorts = [
    'asc' => 'desc',
    'desc' => 'asc',
];

$get = Yii::$app->request->get();

$filter = Yii::$app->request->get('order_param', 'default');
if (!isset($orderFilters[$filter])) $filter = 'default';
$sort = 'asc';
if ($filter != 'default') {
    $sort = Yii::$app->request->get('sort', 'asc');
}
if (!isset($orderSorts[$sort])) $sort = 'asc';

$default = $orderFilters[$filter];
unset($orderFilters[$filter]);
?>

<div class="sort-box">
    <span class="separator">Сортировать:</span>
    <div class="btn-group select-dropdown">
        <button type="button" class="btn select-btn"><?=$default?></button>
        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-angle-down"></i>
        </button>
        <ul class="dropdown-menu" role="menu">
            <?php foreach($orderFilters as $name => $value): ?>
                <li><a href="<?=\yii\helpers\Url::to(\yii\helpers\ArrayHelper::merge([''], $get, ['order_param' => $name]))?>"><?=$value?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php if ($filter !== 'default'): ?>
        <a class="btn-arrow order-<?=$sort?> left" about="Порядок сортировки" href="<?=\yii\helpers\Url::to(\yii\helpers\ArrayHelper::merge([''], $get, ['sort' => $orderSorts[$sort]]))?>"></a>
    <?php endif; ?>
</div>

