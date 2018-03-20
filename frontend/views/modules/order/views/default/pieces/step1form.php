<?php
/* @var $this yii\web\View */

/* @var $step1form \common\modules\order\forms\frontend\Step1Form */

?>

<?php $form = kartik\form\ActiveForm::begin([
    'options' => [
        'data' => [
            'type' => 'stepForm',
            'pjax' => 'true',
        ],
    ],
]); ?>
<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <h2 class="checkout-title">Новый покупатель</h2>
        <p>Как вы предпочитаете продолжить оформление заказа?</p>
        <div class="xs-margin"></div>
        <?= $form->field($step1form, 'orderMode')->radioList(
            [
                \common\modules\order\forms\frontend\Step2Form::SCENARIO_GUEST => 'Продолжить как гость',
                'login' => 'Войти под своим именем',
                \common\modules\order\forms\frontend\Step2Form::SCENARIO_REGISTER => 'Зарегистрироваться',
            ], [
                'item' => function($index, $label, $name, $checked, $value) use ($form){
                    $opts = [
                        'data-index' => $index,
                        'label' => '<span class="checbox-container"><i class="fa fa-check"></i></span>' . $label,
                        'value' => $value,
                        'disabled' => $form->disabled,
                        'readonly' => $form->readonly,
                    ];
                    $labelOptions = [];
                    if ($form->disabled) {
                        \yii\helpers\Html::addCssClass($labelOptions, 'disabled');
                        $opts['disabled'] = true;
                    }
                    if ($form->readonly) {
                        \yii\helpers\Html::addCssClass($labelOptions, 'disabled');
                        $opts['readonly'] = true;
                    }
                    $opts['labelOptions'] = $labelOptions;
                    $out = \yii\helpers\Html::radio($name, $checked, $opts);
                    $out = \yii\helpers\Html::tag('div', $out, ['class' => 'radio']);
                    return \yii\helpers\Html::tag('div', $out, ['class' => 'custom-checkbox sm-margin']);
                }
            ]
        )->label(false) ?>

        <p>Зарегистрировавшись, Вы сможете делать покупки гораздо быстрее. Избранные
            товары будут оставаться в разделе "Избранное". Будет сохраняться история заказов. Также, Вы сможете
            воспользоваться персональными скидками и другими программами нашего
            магазина.
        </p>
        <div class="md-margin"></div>

    </div><!-- End .col-md-6 -->

    <div class="col-md-6 col-sm-6 col-xs-12" id="loginFormContainer" style="display: none">
        <h2 class="checkout-title">Уже зарегистрированы?</h2>
        <p>Войдите под своим аккаунтом, и... Добро пожаловать к нам вновь!</p>
        <div class="xs-margin"></div>

        <div class="input-group">
            <span class="input-group-addon">
                <span class="input-icon input-icon-email"></span>
                <span class="input-text">
                    <?= $step1form->getAttributeLabel('username') ?>&#42;
                </span>
            </span>
            <?= $form->field($step1form, 'username')->textInput([
                'class' => 'form-control input-lg',
                'placeholder' => 'Ваш ' . $step1form->getAttributeLabel('username'),
            ])->label(false) ?>
        </div><!-- End .input-group -->
        <div class="input-group xs-margin">
            <span class="input-group-addon">
                <span class="input-icon input-icon-password"></span>
                <span class="input-text"><?= $step1form->getAttributeLabel('password') ?>&#42;</span>
            </span>
            <?= $form->field($step1form, 'password')->passwordInput([
                'class' => 'form-control input-lg',
                'placeholder' => 'Ваш ' . $step1form->getAttributeLabel('password'),
            ])->label(false) ?>
        </div><!-- End .input-group -->
        <span class="help-block text-right">
            <a href="<?= \yii\helpers\Url::toRoute('/site/request-password-reset') ?>">Забыли пароль?</a>
        </span>
        <div class="input-group custom-checkbox sm-margin top-10px">
            <?= $form->field($step1form, 'rememberMe')->checkbox([
                'label' => '
                <span class="checbox-container">
                    <i class="fa fa-check"></i>
                </span>'
                    . $step1form->getAttributeLabel('rememberMe'),
            ]) ?>
        </div><!-- End .input-group -->
    </div><!-- End .col-md-6 -->
</div><!-- End.row -->
<?= \yii\helpers\Html::submitButton('Продолжить', [
    'class' => 'btn btn-custom-2',
    'data-action' => 'next-step'
]) ?>
<?php kartik\form\ActiveForm::end(); ?>

<?php common\helpers\ViewHelper::startRegisterScript($this); ?>
<script>
    $(function() {
        function setLoginVisible() {
            show = $('input[name="Step1Form[orderMode]"]:checked').val();
            formContainer = $('#' + show + 'FormContainer');
            $(formContainer).toggle(show);
            $(formContainer).find('input').prop( "disabled", !show);
        }
        setLoginVisible();
        $(document).delegate('input[name="Step1Form[orderMode]"]', 'click', function () {
            setLoginVisible();
        });
    });
</script>
<?php common\helpers\ViewHelper::endRegisterScript(); ?>
