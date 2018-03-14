<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\forms\SignupForm */
$this->title = 'Регистрация аккаунта';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <header class="content-title">
                <h1 class="title">Регистрация аккаунта</h1>
                <p class="title-desc">
                    Если Вы уже зарегистрированы, пожалуйста перейдите на
                    <a href="<?=\yii\helpers\Url::toRoute('/site/login')?>">
                        страницу авторизации
                    </a>
                    .
                </p>
            </header>
            <div class="xs-margin"></div><!-- space -->
            
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
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <h2 class="checkout-title">Персональные данные</h2>
                    
                    <?php $formFields = [
                        'first_name' => 'icon:input-icon-user',
                        'last_name' => 'icon:input-icon-user',
                        'username' => 'icon:input-icon-user',
                        'email' => 'icon:input-icon-email',
                        'phone_number' => 'icon:input-icon-phone',
                        'company' => 'icon:input-icon-company',
                    ] ?>
                    
                    <?php $subform = 'user'; ?>
                    <?= $this->render('signupFormGenerate', compact('model', 'form', 'formFields', 'subform')) ?>
                </div><!-- End .col-md-6 -->
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <fieldset>
                        <h2 class="checkout-title">Ваш адрес</h2>
    
                        <?php $formFields = [
                            'address' => 'icon:input-icon-address',
                            'city' => 'icon:input-icon-city',
                            'region' => 'icon:input-icon-region',
                        ] ?>
    
                        <?php $subform = 'userAddresses'; ?>
                        <?= $this->render('signupFormGenerate', compact('model', 'form', 'formFields', 'subform')) ?>
                    </fieldset>
                    <h2 class="sub-title">Ваш пароль</h2>
                    <?php $formFields = [
                        'password' => 'type:password;icon:input-icon-password',
                        'password_confirm' => 'type:password;icon:input-icon-password',
                    ] ?>
                    
                    <?php $subform = 'user'; ?>
                    <?= $this->render('signupFormGenerate', compact('model', 'form', 'formFields', 'subform')) ?>
                </div><!-- End .col-md-6 -->
            </div><!-- End row -->
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <firldset>
                        <?php $formFields = [
                            'agree_to_news' => 'type:checkbox',
                            'privacy_agree' => 'type:checkbox'
                        ] ?>
                        
                        <?php $subform = 'user'; ?>
                        <?= $this->render('signupFormGenerate', compact('model', 'form', 'formFields', 'subform')) ?>
                
                        <?= \yii\helpers\Html::submitButton('Создать аккаунт', [
                            'class' => 'btn btn-custom-2',
                        ]) ?>
                    </firldset>
                </div>
            </div>
            <?php \kartik\widgets\ActiveForm::end(); ?>
        </div><!-- End .col-md-12 -->
    </div><!-- End .row -->
</div><!-- End .container -->
