<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
$items = [
    ['label' => 'Rule', 'url' => ['/rbac/rule']],
    ['label' => 'Permission', 'url' => ['/rbac/permission']],
    ['label' => 'Role', 'url' => ['/rbac/role']],
    ['label' => 'Assignment', 'url' => ['/rbac/assignment']],
];
?>

<div class="site-index">
    <?= yii\widgets\Menu::widget(['items' => $items]); ?>
</div>
