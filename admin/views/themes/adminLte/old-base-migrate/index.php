<?php

/* @var $this yii\web\View */
/* @var $csvFormModel backend\models\forms\baseMigrate\CsvForm */
/* @var $csvType string */

use yii\helpers\Url;
use \yii\widgets\ActiveForm;

$this->title = 'Импортирование каталога с 8str.ru';
?>
<div class="site-index">
    <div class="container">
        <div class="col-md-4">
            <b>1.</b>
            <?php if (empty($auxFieldsAdded)): ?>
                <a href="<?=Url::current(['make-aux-fields'])?>">Добавить вспомогательные поля для миграции</a>
            <?php else: ?>
                <a href="<?=Url::current(['remove-aux-fields'])?>">Удалить вспомогательные поля для миграции</a>
            <?php endif; ?>
        </div>

        <div class="clearfix"></div>
        <hr/>

        <div class="col-md-3">
            <?php $csvForm = ActiveForm::begin([
                    'options' => [
                        'enctype' => 'multipart/form-data',
                    ],
                    'action' => Url::current(['get-csv']),
                    'id' => 'csvFileForm'
                ]
            ); ?>
            <span class="text-bold">2. Загрузите csv</span>
            <?=$csvForm->field($csvFormModel, 'file')
                ->fileInput(['disabled' => empty($auxFieldsAdded)])
                ->label(false)
            ?>
            <?=\yii\helpers\Html::submitButton(
                    'Загрузить', [
                            'class' => 'btn btn-primary',
                        'disabled' => empty($auxFieldsAdded)
                    ])
            ?>
            <?php ActiveForm::end(); ?>
        </div>
        <?php if (!empty($csvFile)): ?>
            <div class="col-md-2">
                Загруженный файла:
            </div>
            <div class="col-md-1"><?=round($csvFile/1024/1024, 2)?> Mb</div>
            <div class="col-md-3"><b>Тип:</b> <?=$csvType?></div>
            <div class="clearfix"></div>
            <hr/>
            <div class="col-md-2">
                <span class="text-bold">3.</span>
                <a href="<?=Url::current(['parse-csv'])?>">Разобрать CSV</a>
            </div>
        <?php endif; ?>
    </div>
</div>
