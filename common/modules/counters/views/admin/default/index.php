<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\modules\counters\models\CountersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Счетчики и скрипты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="counters-index">

    <?php Pjax::begin(); ?>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Counters', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'position',
            'included_pages:ntext',
            'excluded_pages:ntext',
            'created_at',
            'created_by',
            'modified_at',
            'modified_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
