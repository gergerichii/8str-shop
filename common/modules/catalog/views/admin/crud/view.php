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
            'desc:raw',
            'status',
            'count',
            'show_on_home',
            'on_list_top',
            'market_upload',
            'files',
            'delivery_time:datetime',
            'created_at',
            'modified_at',
            'creator_id',
            'modifier_id',
            'product_type_id',
            'brand_id',
            'main_rubric_id',
            'old_id',
            'old_rubric_id',
            'model',
            'vendor_code',
            'barcode',
            'warranty',
            'delivery_days',
        ],
    ]) ?>

</div>
