<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/**
 * @var \yii\sphinx\ActiveDataProvider $provider
 * @var \common\modules\catalog\models\ProductSphinxSearch $search
 */

?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Search using sphinx</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <form role="form">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Text</label>
                            <input name="ProductSphinxSearch[q]" type="text" class="form-control" placeholder="Enter ..." value="<?= Html::encode($search->q); ?>">
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">Search</button>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>

            <?= ListView::widget([
                'dataProvider' => $provider,
                'itemView' => '_product',
            ]); ?>
        </div>
    </div>
</section>