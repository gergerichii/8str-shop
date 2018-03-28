<?php

use \common\modules\order\forms\frontend\OrderForm;

/* @var $this yii\web\View */
/* @var OrderForm $orderForm*/

$step = $orderForm->orderStep;
/** @var \common\models\forms\LoginForm $loginForm */
$loginForm = $orderForm->loginForm;
/** @var \common\models\forms\SignupForm $signupForm */
$signupForm = $orderForm->signupForm;

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <header class="content-title">
                <h1 class="title">Оформление заказа</h1>
                <p class="title-desc">Этот волнующим момент...</p>
            </header>
            <div class="xs-margin"></div><!-- space -->
            <div class="panel-group custom-accordion" id="checkout">
                <?php foreach($orderForm->scenarioSteps as $formStep => $formData): ?>
                <div class="panel" data-step="<?=$formStep?>">
                    <div class="accordion-header">
                        <div class="accordion-title"><?=$formStep?> Шаг: <span><?=$formData['title']?></span></div>
                        <!-- End .accordion-title -->
                        <?php $disabled = ($formStep > $orderForm->orderStep) ? 'style="display:none;"' : '';?>
                        <a <?=$disabled?> class="accordion-btn <?= $step == $formStep ? 'opened' : '' ?>" data-toggle="collapse" data-parent="#checkout" data-target="#<?=$formData['name']?>"></a>
                    </div><!-- End .accordion-header -->

                    <div id="<?=$formData['name']?>" class="collapse <?= $step == $formStep ? 'in' : '' ?>">
                        <div class="panel-body">
                            <?php \yii\widgets\Pjax::begin(['id' => 'step' . $formStep]); ?>
                            <?php if(!$disabled) echo $this->render("pieces/{$formData['name']}", compact('orderForm')); ?>
                            <?php \yii\widgets\Pjax::end(); ?>
                        </div><!-- End .panel-body -->
                    </div><!-- End .panel-collapse -->

                </div><!-- End .panel -->
                <?php endforeach; ?>
            </div><!-- End .panel-group #checkout -->
            <div class="xlg-margin"></div><!-- space -->
        </div><!-- End .col-md-12 -->
    </div><!-- End .row -->
</div><!-- End .container -->

<?php common\helpers\ViewHelper::startRegisterScript($this); ?>
<script>
    $(document).ready(function() {
        setTimeout(function () {
            $('html, body').animate({scrollTop: $('.panel[data-step=<?=$step?>]').offset().top-70}, 1000);
        }, 1000);
        
    });
    $(document).delegate('[data-action=next-step]', 'click', function(){
        panel = $(this).parents('.panel');
        var nextStep = $(panel).data('step') + 1;
        var nextPanel = $(panel).siblings('.panel[data-step=' + nextStep + ']');
        var pjaxContainer = $(this).parents('[data-pjax-container]');
        var nextPjaxContainerId = false;
        if($(nextPanel).find('[data-pjax-container]').length) {
            nextPjaxContainerId = '#' + $(nextPanel).find('[data-pjax-container]').attr('id');
        }
        
        /* Если */
        if (pjaxContainer.length && $(this).attr('href') !== '#') {
            $(pjaxContainer).one('pjax:success', function(a,b,c,d){
                if (d.status == 200) {
                    if (nextPjaxContainerId) {
                        $.pjax.reload({container: nextPjaxContainerId, "timeout" : 10000});
                        $(nextPjaxContainerId).one('pjax:success', function(a,b,c,d){
                            if (d.status == 200) {
                                $(nextPanel).find('a.accordion-btn').toggle(true);
                                scrollToNext();
                            }
                        });
                    } else {
                        scrollToNext();
                    }
                }
            });
            ret = true;
        } else {
            if (nextPjaxContainerId) {
                $.pjax.reload({container: nextPjaxContainerId, "timeout" : 10000});
                $(nextPjaxContainerId).one('pjax:success', function(a,b,c,d){
                    if (d.status == 200) {
                        scrollToNext();
                    }
                });
                ret = false;
            } else {
                scrollToNext();
                ret = false;
            }
        }
        
        return ret;
        
        function scrollToNext () {
            $(nextPanel).find('.accordion-header a').click();
            scrollDest = $(panel).offset().top;
            $('html, body').animate({scrollTop: scrollDest}, 500);
        }
    });
</script>
<?php common\helpers\ViewHelper::endRegisterScript(); ?>
