<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 06.06.2018
 * Time: 10:31
 */
/** @var $this \yii\web\View */
$items = [
    [
        'label' => 'tab1',
        'content' => 'Tab 1 content',
    ],
    [
        'label' => 'tab2',
        'content' => 'Tab 2 content',
    ],
];
?>

<?= \common\widgets\AdaptiveTabs::widget([
    'id' => 'main-tabs',
    'items' => $items,
    'bordered' => false,
    'position' => \kartik\tabs\TabsX::POS_ABOVE,
]) ?>
