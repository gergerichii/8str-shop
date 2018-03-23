<?php
/* @var $this yii\web\View */

/* @var $model \common\models\forms\SignupForm */
/* @var $formFields array */
/* @var $subform string */

/* @var $form \kartik\widgets\ActiveForm */

$model = $model->$subform;
$prefix = '';
if (is_array($model)) {
    $model = reset($model);
    $prefix = '[0]';
}
?>


<?php foreach($model->activeAttributes() as $field): ?>
    <?php if (!isset($formFields[$field])) continue; ?>
    <?php preg_match('/(?:type:(?P<type>[^;]*);?)?(?:icon:(?P<icon>[^;]*))?/', $formFields[$field],$matches) ?>
    <?php if (empty($matches['type']) || in_array($matches['type'], ['text', 'password'])): ?>
        <div class="input-group">
            <span class="input-group-addon">
                <span class="input-icon <?= $matches['icon'] ?>"></span>
                <span class="input-text">
                    <?= $model->getAttributeLabel($field) ?><?= $model->isAttributeRequired($field) ? '&#42;' : ''?>
                </span>
            </span>
            <?= $form->field($model, $prefix.$field)->input($matches['type'],[
                'class' => 'form-control input-lg',
                'placeholder' => 'Заполните ' . $model->getAttributeLabel($field),
            ])->label(false); ?>
        </div><!-- End .input-group -->
    <?php elseif(in_array($matches['type'], ['checkbox', 'radio'])): ?>
        <div class="input-group custom-checkbox sm-margin">
            <?= $form->field($model, $prefix.$field)->checkbox([
                'label' => '
                <span class="checbox-container">
                    <i class="fa fa-check"></i>
                </span>'
                    . $model->getAttributeLabel($field),
            ])->error(false) ?>

        </div><!-- End .input-group -->
    <?php endif; ?>
<?php endforeach; ?>
