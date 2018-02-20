<?php
/* @var $this yii\web\View */

/* @var $step1form \common\modules\order\forms\frontend\Step1Form */
/* @var $step2form \common\modules\order\forms\frontend\Step2Form */

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
                <div class="panel" data-step="2">
                    <div class="accordion-header">
                        <div class="accordion-title">2 Шаг: <span>Персональные данные</span></div>
                        <!-- End .accordion-title -->
                        <a class="accordion-btn <?= $step == 2 ? 'opened' : '' ?>" data-toggle="collapse" data-parent="#checkout" data-target="#billing"></a>
                    </div><!-- End .accordion-header -->

                    <div id="billing" class="collapse <?= $step == 2 ? 'in' : '' ?>">
                        <div class="panel-body">
                            <?php \yii\widgets\Pjax::begin(['id' => 'step2']); ?>
                                <?php echo $this->render('pieces/step2form', compact('step2form')); ?>
                            <?php \yii\widgets\Pjax::end(); ?>
                        </div><!-- End .panel-body -->
                    </div><!-- End .panel-collapse -->

                </div><!-- End .panel -->
                <div class="panel" data-step="3">
                    <div class="accordion-header">
                        <div class="accordion-title">3 Шаг: <span>Информация о доставке</span></div>
                        <!-- End .accordion-title -->
                        <a class="accordion-btn <?= $step == 3 ? 'opened' : '' ?>" data-toggle="collapse"
                           data-parent="#checkout" data-target="#delivery-details"></a>
                    </div><!-- End .accordion-header -->

                    <div id="delivery-details" class="collapse <?= $step == 3 ? 'in' : '' ?>">
                        <div class="panel-body">
                            <p>Details about delivery</p>
                            <a href="#" class="btn btn-custom-2" role="button" data-action="next-step">Продолжить</a>
                        </div><!-- End .panel-body -->
                    </div><!-- End .panel-collapse -->

                </div><!-- End .panel -->
                <div class="panel" data-step="4">
                    <div class="accordion-header">
                        <div class="accordion-title">4 Step: <span>Delivery Method</span></div>
                        <!-- End .accordion-title -->
                        <a class="accordion-btn" data-toggle="collapse" data-parent="#checkout" data-target="#delivery-method"></a>
                    </div><!-- End .accordion-header -->

                    <div id="delivery-method" class="collapse">
                        <div class="panel-body">
                            <p>Choose your delivery method.</p>
                            <a href="#" class="btn btn-custom-2" role="button" data-action="next-step">Продолжить</a>
                        </div><!-- End .panel-body -->
                    </div><!-- End .panel-collapse -->

                </div><!-- End .panel -->
                <div class="panel" data-step="5">
                    <div class="accordion-header">
                        <div class="accordion-title">5 Step: <span>Payment Method</span></div>
                        <!-- End .accordion-title -->
                        <a class="accordion-btn" data-toggle="collapse" data-parent="#checkout" data-target="#payment-method"></a>
                    </div><!-- End .accordion-header -->

                    <div id="payment-method" class="collapse">
                        <div class="panel-body">
                            <p>Choose your payment method.</p>
                            <a href="#" class="btn btn-custom-2" role="button" data-action="next-step">Продолжить</a>
                        </div><!-- End .panel-body -->
                    </div><!-- End .panel-collapse -->

                </div><!-- End .panel -->
                <div class="panel" data-step="6">
                    <div class="accordion-header">
                        <div class="accordion-title">6 Step: <span>Confirm Order</span></div>
                        <!-- End .accordion-title -->
                        <a class="accordion-btn opened" data-toggle="collapse" data-parent="#checkout" data-target="#confirm"></a>
                    </div><!-- End .accordion-header -->

                    <div id="confirm" class="collapse in">
                        <div class="panel-body">

                            <div class="table-responsive">
                                <table class="table checkout-table">
                                    <thead>
                                    <tr>
                                        <th class="table-title">Product Name</th>
                                        <th class="table-title">Product Code</th>
                                        <th class="table-title">Unit Price</th>
                                        <th class="table-title">Quantity</th>
                                        <th class="table-title">SubTotal</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <tr>
                                        <td class="item-name-col">
                                            <figure>
                                                <a href="#"><img src="images/products/compare1.jpg"
                                                                 alt="Lowlands Lace Blouse"></a>
                                            </figure>
                                            <header class="item-name"><a href="#">Lowlands Lace Blouse</a></header>
                                            <ul>
                                                <li>Color: White</li>
                                                <li>Size: SM</li>
                                            </ul>
                                        </td>
                                        <td class="item-code">MP125984154</td>
                                        <td class="item-price-col"><span class="item-price-special">$1175</span>
                                        </td>
                                        <td>
                                            <div class="custom-quantity-input">
                                                <input type="text" name="quantity" value="1">
                                                <a href="#" onclick="return false;"
                                                   class="quantity-btn quantity-input-up"><i
                                                        class="fa fa-angle-up"></i></a>
                                                <a href="#" onclick="return false;"
                                                   class="quantity-btn quantity-input-down"><i
                                                        class="fa fa-angle-down"></i></a>
                                            </div>
                                        </td>
                                        <td class="item-total-col"><span class="item-price-special">$1175</span>
                                            <a href="#" class="close-button"></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item-name-col">
                                            <figure>
                                                <a href="#"><img src="images/products/compare2.jpg"
                                                                 alt="Samsung Galaxy Ace"></a>
                                            </figure>
                                            <header class="item-name"><a href="#">Samsung Galaxy Ace</a></header>
                                            <ul>
                                                <li>Color: Black</li>
                                                <li>Size: XL</li>
                                            </ul>
                                        </td>
                                        <td class="item-code">MP125984154</td>
                                        <td class="item-price-col"><span class="item-price-special">$1475</span>
                                        </td>
                                        <td>
                                            <div class="custom-quantity-input">
                                                <input type="text" name="quantity" value="1">
                                                <a href="#" onclick="return false;"
                                                   class="quantity-btn quantity-input-up"><i
                                                        class="fa fa-angle-up"></i></a>
                                                <a href="#" onclick="return false;"
                                                   class="quantity-btn quantity-input-down"><i
                                                        class="fa fa-angle-down"></i></a>
                                            </div>
                                        </td>
                                        <td class="item-total-col"><span class="item-price-special">$1475</span>
                                            <a href="#" class="close-button"></a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="checkout-table-title" colspan="4">Subtotal:</td>
                                        <td class="checkout-table-price">$399.44</td>
                                    </tr>
                                    <tr>

                                        <td class="checkout-table-title" colspan="4">Shipping:</td>
                                        <td class="checkout-table-price">$6.00</td>
                                    </tr>
                                    <tr>

                                        <td class="checkout-table-title" colspan="4">Tax(0%):</td>
                                        <td class="checkout-table-price">$0.00</td>
                                    </tr>

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td class="checkout-total-title" colspan="4"><strong>TOTAL:</strong></td>
                                        <td class="checkout-total-price cart-total"><strong>$434.50</strong></td>
                                    </tr>
                                    </tfoot>
                                </table>

                            </div><!-- End .table-reponsive -->
                            <div class="lg-margin"></div><!-- space -->
                            <div class="text-right">
                                <input type="submit" class="btn btn-custom-2" value="CONFIRM ORDER">
                            </div>
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
