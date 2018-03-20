<?php
/* @var $this yii\web\View */

$forms = [
    [
        'name' => 'СПОСОБ АВТОРИЗАЦИИ',
        'template' => 'authorization',
        'forms' => ['signup', 'login'],
    ],
    [
        'name' => 'Информация по доставке',
        'template' => 'deliveryInformation',
        'forms' => [],
    ],
    [
        'name' => 'Способ оплаты',
        'template' => 'paymentMethod',
        'forms' => [],
    ],
    [
        'name' => 'Подтверждение заказа',
        'template' => 'confirmOrder',
        'forms' => [],
    ],
];

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
                <div class="panel" data-step="1">
                    <div class="accordion-header">
                        <div class="accordion-title">1 Шаг: <span>Способ авторизации</span></div>
                        <!-- End .accordion-title -->
                        <a class="accordion-btn <?= $step == 1 ? 'opened' : '' ?>" data-toggle="collapse" data-parent="#checkout" data-target="#checkout-option"></a>
                    </div><!-- End .accordion-header -->

                    <div id="checkout-option" class="collapse <?= $step == 1 ? 'in' : '' ?>">
                        <div class="panel-body">
                            <?php \yii\widgets\Pjax::begin(['id' => 'step1']); ?>
                                <?php if (!yii::$app->getUser()->getIsGuest()): ?>
                                    <p>
                                        Вы уже авторизованы как <b><?= yii::$app->getUser()->identity->username ?> </b>
                                    </p>
                                    <p>
                                        <a href="#" class="btn btn-custom-2" role="button" data-action="next-step">Продолжить</a>
                                    </p>
                                <?php else: ?>
                                    <?php echo $this->render('pieces/step1form', compact('step1form')); ?>
                                <?php endif; ?>
                            <?php \yii\widgets\Pjax::end(); ?>
                        </div><!-- End .panel-body -->
                    </div><!-- End .panel-collapse -->

                </div><!-- End .panel -->

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
        nextStep = $(panel).data('step') + 1;
        nextPanel = $(panel).siblings('.panel[data-step=' + nextStep + ']');
        pjaxContainer = $(this).parents('[data-pjax-container]');
        if($(nextPanel).find('[data-pjax-container]').length) {
            nextPjaxContainerId = '#' + $(nextPanel).find('[data-pjax-container]').attr('id');
        } else {
            nextPjaxContainerId = false;
        }
        if (pjaxContainer.length && $(this).attr('href') !== '#') {
            $(pjaxContainer).one('pjax:success', function(a,b,c,d){
                if (d.status == 200) {
                    if (nextPjaxContainerId) {
                        $.pjax.reload({container: nextPjaxContainerId, "timeout" : 10000});
                        $(nextPjaxContainerId).one('pjax:success', function(a,b,c,d){
                            if (d.status == 200) {
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
