<?php

use common\helpers\ViewHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\counters\models\Counters */
/* @var $form yii\widgets\ActiveForm */

/** Перевести в Ассет */
$this->registerJsFile('/js/ace-src-min-noconflict/ace.js', ['position' => \yii\web\View::POS_HEAD]);
$editorText = ($model->value) ? $model->value : '';
?>
<?php ViewHelper::startRegisterCss($this); ?>
<style type="text/css" media="screen">

    .ace_editor {
        border: 1px solid lightgray;
        margin: auto;
        height: 200px;
        width: 80%;
    }

    .scrollmargin {
        height: 80px;
        text-align: center;
    }
</style>
<?php ViewHelper::endRegisterCss(); ?>


<div class="counters-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'value')->hiddenInput() ?>

    <pre id="editor">Инициализация редактора скриптов...</pre><!-- Editor -->

    <?= $form->field($model, 'position')->dropDownList($model::POSITIONS)?>

    <?= $form->field($model, 'included_pages')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'excluded_pages')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php $this->registerJsVar('editorText', $editorText) ?>
<?php ViewHelper::startRegisterScript($this, $this::POS_LOAD) ?>
<script>
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/tomorrow");
    editor.session.setMode("ace/mode/html");
    editor.setAutoScrollEditorIntoView(true);
    editor.setOption("minLines", 30);
    editor.setOption("maxLines", 30);
    editor.setValue(editorText, 1);
    editor.on('change', function(){
        $('#counters-value').val(editor.getValue());
    });
</script>
<?php ViewHelper::endRegisterScript() ?>

