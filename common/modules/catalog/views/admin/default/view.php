<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\catalog\models\Product */
?>
<div class="product-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'title',
            'desc:html',
            'created_at:datetime',
            'modified_at:datetime',
            [
                'label' => 'Brand',
                'value' => function ($model) {
                    if ($model->brand && $model->brand->name) {
                        return $model->brand->name;
                    } else {
                        return null;
                    }
                },
            ],
            [
                'label' => 'Main category',
                'value' => function ($model) {
                    if ($model->mainRubric && $model->mainRubric->name) {
                        return $model->mainRubric->name;
                    } else {
                        return null;
                    }
                },
            ],
        ],
    ]); ?>

</div>
