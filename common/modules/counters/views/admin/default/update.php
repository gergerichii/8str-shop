<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\counters\models\Counters */

$this->title = 'Редактирование счетчиков';
$this->params['breadcrumbs'][] = ['label' => 'Counters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="counters-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
