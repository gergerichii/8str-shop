<?php

use \kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \common\models\forms\SignupForm */
/* @var $form ActiveForm */
/* @var $disabled bool */
?>
<h2 class="checkout-title">Персональные данные</h2>

<?php $formFields = [
    'first_name' => 'icon:input-icon-user',
    'last_name' => 'icon:input-icon-user',
    'email' => 'icon:input-icon-email',
    'phone_number' => 'icon:input-icon-phone',
    'agree_to_news' => 'type:checkbox',
    'privacy_agree' => 'type:checkbox'
] ?>
<?php $subform = 'user'; ?>
<?= $this->render('signupFormGenerate', compact('model', 'form', 'formFields', 'subform', 'disabled')) ?>
