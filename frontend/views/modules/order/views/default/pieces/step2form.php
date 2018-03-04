<?php
/* @var $this yii\web\View */

/* @var $step2form \common\modules\order\forms\frontend\Step2Form */

?>

<div class="row">
    <?php $form = \kartik\widgets\ActiveForm::begin([
        'type' => \kartik\form\ActiveForm::TYPE_VERTICAL,
        'readonly' => !Yii::$app->user->isGuest,
        'fieldConfig' => [
            'autoPlaceholder' => false,
        ],
        'options' => [
            'data' => [
                'type' => 'stepForm',
                'pjax' => 'true',
            ],
        ],
    ])?>
    <div class="col-md-6 col-sm-6 col-xs-12">

        <h2 class="checkout-title">Персональные данные</h2>
        
        <?php $formFields = [
            'firstName' => 'icon:input-icon-user',
            'lastName' => 'icon:input-icon-user',
            'login' => 'icon:input-icon-user',
            'email' => 'icon:input-icon-email',
            'phoneNumber' => 'icon:input-icon-phone',
            'company' => 'icon:input-icon-company',
            'password' => 'type:password;icon:input-icon-password',
            'passwordConfirm' => 'type:password;icon:input-icon-password',
            'agreeToNews' => 'type:checkbox'
        ] ?>
        
        <?= $this->render('step2formGenerate', compact('step2form', 'form', 'formFields')) ?>
    </div><!-- End .col-md-6 -->

    <div class="col-md-6 col-sm-6 col-xs-12">
        <h2 class="checkout-title">Ваш адрес</h2>

        <?php $formFields = [
            'address' => 'icon:input-icon-address',
            'city' => 'icon:input-icon-city',
            'region' => 'icon:input-icon-region',
            'privacyAgree' => 'type:checkbox'
        ] ?>
        
        <?= $this->render('step2formGenerate', compact('step2form', 'form', 'formFields')) ?>

        <?= \yii\helpers\Html::submitButton('Продолжить', [
            'class' => 'btn btn-custom-2',
            'data-action' => 'next-step'
        ]) ?>
    </div><!-- End .col-md-6 -->
    <?php \kartik\widgets\ActiveForm::end(); ?>

</div><!-- End .row -->
