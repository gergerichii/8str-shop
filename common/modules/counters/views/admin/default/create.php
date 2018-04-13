<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\counters\models\Counters */

$this->title = 'Добавить счетчик или скрипт';
$this->params['breadcrumbs'][] = ['label' => 'Счетчики и скрипты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="counters-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
