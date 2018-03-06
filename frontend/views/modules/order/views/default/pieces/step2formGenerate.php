<?php
/* @var $this yii\web\View */

/* @var $step2form \common\modules\order\forms\frontend\Step2Form */
/* @var $formFields array */

/* @var $form \kartik\widgets\ActiveForm */

?>


<?php foreach($step2form->activeAttributes() as $field): ?>
    <?php if (!isset($formFields[$field])) continue; ?>
    <?php preg_match('/(?:type:(?P<type>[^;]*);?)?(?:icon:(?P<icon>[^;]*))?/', $formFields[$field],$matches) ?>
    <?php if (empty($matches['type']) || in_array($matches['type'], ['text', 'password'])): ?>
        <div class="input-group">
            <span class="input-group-addon">
                <span class="input-icon <?= $matches['icon'] ?>"></span>
                <span class="input-text">
                    <?= $step2form->getAttributeLabel($field) ?><?= $step2form->isAttributeRequired($field) ? '&#42;' : ''?>
                </span>
            </span>
            <?= $form->field($step2form, $field)->input($matches['type'],[
                'class' => 'form-control input-lg',
                'placeholder' => 'Заполните ' . $step2form->getAttributeLabel($field),
            ])->label(false); ?>
        </div><!-- End .input-group -->
    <?php elseif(in_array($matches['type'], ['checkbox', 'radio'])): ?>
        <div class="input-group custom-checkbox sm-margin">
            <?= $form->field($step2form, $field)->checkbox([
                'label' => '
                <span class="checbox-container">
                    <i class="fa fa-check"></i>
                </span>'
                    . $step2form->getAttributeLabel($field),
            ])->error(false) ?>

        </div><!-- End .input-group -->
    <?php endif; ?>
<?php endforeach; ?>
