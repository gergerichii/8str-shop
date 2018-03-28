<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 21.03.2018
 * Time: 18:51
 */

use common\modules\order\forms\frontend\OrderForm;

/** @var \yii\web\View $this */
/** @var \common\modules\order\forms\frontend\OrderForm $orderForm */
/** @var \common\models\forms\LoginForm $loginForm */
$loginForm = $orderForm->loginForm;
/** @var \common\models\forms\SignupForm $signupForm */
$signupForm = $orderForm->signupForm;
/** @var \yii\web\User $user */
$user = yii::$app->getUser();
?>

<?php if (!$user->getIsGuest() && $user->getIdentity()->status !== \common\models\entities\User::STATUS_GUEST): ?>
    <p>
        Вы уже авторизованы как <b><?= $user->identity->username ?> </b>
    </p>
    <p>
        <a href="#" class="btn btn-custom-2" role="button" data-action="next-step">Продолжить</a>
    </p>
<?php else: ?>
    <?php $form = kartik\form\ActiveForm::begin([
        'options' => [
            'data' => [
                'type' => 'stepForm',
                'pjax' => 'true',
            ],
            'id' => 'authorizationForm'
        ],
    ]); ?>

    <?=$form->field($orderForm, 'orderStep', ['inputOptions' => ['value' => 1, 'id' => false]])->hiddenInput()->label(false)?>
    
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h2 class="checkout-title">Новый покупатель</h2>
            <p>Как вы предпочитаете продолжить оформление заказа?</p>
            <div class="xs-margin"></div>
            <?= $form->field($orderForm, 'orderMode')->radioList(
                [
                    OrderForm::ORDER_MODE_GUEST => 'Продолжить как гость',
                    OrderForm::ORDER_MODE_LOGIN => 'Войти под своим именем',
                    OrderForm::ORDER_MODE_REGISTER => 'Зарегистрироваться',
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
            <?= \yii\helpers\Html::submitButton('Продолжить', [
                'class' => 'btn btn-custom-2',
                'data-action' => 'next-step'
            ]) ?>
    
        </div><!-- End .col-md-6 -->
    
        <?php $hide = ($orderForm->orderMode !== OrderForm::ORDER_MODE_LOGIN) ? 'style="display: none"' : ''; ?>
        <div class="col-md-6 col-sm-6 col-xs-12 authorizationFormContainer" id="loginFormContainer" <?=$hide?>>
            <h2 class="checkout-title">Уже зарегистрированы?</h2>
            <p>Войдите под своим аккаунтом, и... Добро пожаловать к нам вновь!</p>
            <div class="xs-margin"></div>
    
            <div class="input-group">
                <span class="input-group-addon">
                    <span class="input-icon input-icon-email"></span>
                    <span class="input-text">
                        <?= $loginForm->getAttributeLabel('username') ?>&#42;
                    </span>
                </span>
                <?= $form->field($loginForm, 'username')->textInput([
                    'class' => 'form-control input-lg',
                    'placeholder' => 'Ваш ' . $loginForm->getAttributeLabel('username'),
                    'disabled' => (bool)$hide,
                ])->label(false) ?>
            </div><!-- End .input-group -->
            <div class="input-group xs-margin">
                <span class="input-group-addon">
                    <span class="input-icon input-icon-password"></span>
                    <span class="input-text"><?= $loginForm->getAttributeLabel('password') ?>&#42;</span>
                </span>
                <?= $form->field($loginForm, 'password')->passwordInput([
                    'class' => 'form-control input-lg',
                    'placeholder' => 'Ваш ' . $loginForm->getAttributeLabel('password'),
                    'disabled' => (bool)$hide,
                ])->label(false) ?>
            </div><!-- End .input-group -->
            <span class="help-block text-right">
                <a href="<?= \yii\helpers\Url::toRoute('/site/request-password-reset') ?>">Забыли пароль?</a>
            </span>
            <div class="input-group custom-checkbox sm-margin top-10px">
                <?= $form->field($loginForm, 'rememberMe')->checkbox([
                    'label' => '
                    <span class="checbox-container">
                        <i class="fa fa-check"></i>
                    </span>'
                        . $loginForm->getAttributeLabel('rememberMe'),
                    'disabled' => (bool)$hide,
                ]) ?>
            </div><!-- End .input-group -->
        </div><!-- End .col-md-6 -->
        <?php $hide = ($orderForm->orderMode !== OrderForm::ORDER_MODE_GUEST) ? 'style="display: none"' : ''; ?>
        <div class="col-md-6 col-sm-6 col-xs-12 authorizationFormContainer" id="guestFormContainer" <?=$hide?>>
            <h2 class="checkout-title">Не хотите регистрироваться?</h2>
            <p>Заполните минимальные сведения о себе чтобы мы могли Вам оформить заказ.</p>
            <div class="xs-margin"></div>
    
            <?= $this->render('signup', ['model' => $signupForm, 'form' => $form, 'disabled' => (bool)$hide]) ?>
    
        </div><!-- End .col-md-6 -->
    </div><!-- End.row -->
    
    
    <?php kartik\form\ActiveForm::end(); ?>
    
    <?php if(!Yii::$app->request->isPjax): ?>
        <?php common\helpers\ViewHelper::startRegisterScript($this); ?>
        <script>
            $(function() {
                function setFormVisible() {
                    var subForms =$('.authorizationFormContainer');
                    subForms.toggle(false);
                    subForms.find('input').prop( "disabled", true);
                    var subForm = $('input[name="OrderForm[orderMode]"]:checked').val();
                    var subFormContainer = $('#' + subForm + 'FormContainer');
                    if (subForm !== 'register') {
                        $(subFormContainer).find('input').prop( "disabled", false);
                        $(subFormContainer).find('.checkbox.disabled').removeClass( "disabled" );
                        $(subFormContainer).toggle(true);
                    }
                }
                $(document).delegate('input[name="OrderForm[orderMode]"]', 'click', function () {
                    setFormVisible()
                });
            });
        </script>
        <?php common\helpers\ViewHelper::endRegisterScript(); ?>
    <?php endif; ?>
<?php endif; ?>
