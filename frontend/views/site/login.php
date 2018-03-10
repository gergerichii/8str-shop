<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \common\models\forms\LoginForm */

use yii\bootstrap\ActiveForm;

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container site-login">
    <div class="row">
        <div class="col-md-12">
            <header class="content-title">
                <h1 class="title">Авторизуйтесь или создайте аккаунт</h1>
                <div class="md-margin"></div><!-- space -->
            </header>

            <div class="row">

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <h2>Новый клиент</h2>

                    <p>
                        Зарегистрировавшись, Вы сможете делать покупки гораздо быстрее. Избранные
                        товары будут оставаться в разделе "Избранное". Будет сохраняться история заказов. Также, Вы сможете
                        воспользоваться персональными скидками и другими программами нашего
                        магазина.
                    </p>
                    <div class="md-margin"></div><!-- space -->
                    <a href="<?=\yii\helpers\Url::toRoute('signup')?>" class="btn btn-custom-2">Создать аккаунт</a>
                    <div class="lg-margin"></div><!-- space -->
                </div><!-- End .col-md-6 -->
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <h2>Зарегистрированный покупатель</h2>
                    <p>Если вы имеете свой аккаунт, пожалуйста, авторизуйтесь.</p>
                    <div class="xs-margin"></div>

                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <span class="input-icon input-icon-email"></span>
                                <span class="input-text">
                                    <?= $model->getAttributeLabel('username') ?>&#42;
                                </span>
                            </span>
                            <?= $form->field($model, 'username')->textInput([
                                'class' => 'form-control input-lg',
                                'placeholder' => 'Ваш ' . $model->getAttributeLabel('username'),
                            ])->label(false) ?>
                        </div><!-- End .input-group -->
                        <div class="input-group xs-margin">
                            <span class="input-group-addon">
                                <span class="input-icon input-icon-password"></span>
                                <span class="input-text"><?= $model->getAttributeLabel('password') ?>&#42;</span>
                            </span>
                            <?= $form->field($model, 'password')->passwordInput([
                                'class' => 'form-control input-lg',
                                'placeholder' => 'Ваш ' . $model->getAttributeLabel('password'),
                            ])->label(false) ?>
                        </div><!-- End .input-group -->
                        <span class="help-block text-right">
                            <a href="<?= \yii\helpers\Url::toRoute('/site/request-password-reset') ?>">Забыли пароль?</a>
                        </span>
                        <div class="input-group custom-checkbox sm-margin top-10px">
                            <?= $form->field($model, 'rememberMe')->checkbox([
                                'label' => '
                                <span class="checbox-container">
                                    <i class="fa fa-check"></i>
                                </span>'
                                    . $model->getAttributeLabel('rememberMe'),
                            ]) ?>
                        </div><!-- End .input-group -->
                        <button class="btn btn-custom-2">Войти</button>
                    <?php ActiveForm::end(); ?>
                    <div class="sm-margin"></div><!-- space -->
                </div><!-- End .col-md-6 -->

            </div><!-- End.row -->

        </div><!-- End .col-md-12 -->
    </div><!-- End .row -->
</div><!-- End .container -->
