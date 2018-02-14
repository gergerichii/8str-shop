<?php
/* @var $this yii\web\View */

/* @var $step2form \common\modules\order\forms\frontend\Step1Form */
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
                <div class="panel">
                    <div class="accordion-header">
                        <div class="accordion-title">1 Шаг: <span>Способ авторизации</span></div>
                        <!-- End .accordion-title -->
                        <a class="accordion-btn opened" data-toggle="collapse" data-parent="#checkout" data-target="#checkout-option"></a>
                    </div><!-- End .accordion-header -->

                    <div id="checkout-option" class="collapse in">
                        <div class="panel-body">
                            <?php if (!yii::$app->getUser()->getIsGuest()): ?>
                            <p>
                                Вы уже авторизованы как <b><?= yii::$app->getUser()->identity->username ?> </b>
                            </p>
                            <p>
                                <a href="#" class="btn btn-custom-2">CONTINUE</a>
                            </p>
                            <?php else: ?>
                                <?php echo $this->render('pieces/step1form', compact('step1form')); ?>
                            <?php endif; ?>
                        </div><!-- End .panel-body -->
                    </div><!-- End .panel-collapse -->

                </div><!-- End .panel -->
                <div class="panel">
                    <div class="accordion-header">
                        <div class="accordion-title">2 Step: <span>Billing Information</span></div>
                        <!-- End .accordion-title -->
                        <a class="accordion-btn" data-toggle="collapse" data-parent="#checkout" data-target="#billing"></a>
                    </div><!-- End .accordion-header -->

                    <div id="billing" class="collapse">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    <h2 class="checkout-title">Your personal details</h2>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                class="input-icon input-icon-user"></span><span class="input-text">First Name&#42;</span></span>
                                        <input type="text" required class="form-control input-lg"
                                               placeholder="Your First Name">
                                    </div><!-- End .input-group -->
                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                class="input-icon input-icon-user"></span><span class="input-text">Last Name&#42;</span></span>
                                        <input type="text" required class="form-control input-lg"
                                               placeholder="Your Last Lame">
                                    </div><!-- End .input-group -->
                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                class="input-icon input-icon-email"></span><span class="input-text">Email&#42;</span></span>
                                        <input type="text" required class="form-control input-lg"
                                               placeholder="Your Email">
                                    </div><!-- End .input-group -->
                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                class="input-icon input-icon-phone"></span><span class="input-text">Telephone&#42;</span></span>
                                        <input type="text" required class="form-control input-lg"
                                               placeholder="Your Telephone">
                                    </div><!-- End .input-group -->
                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                class="input-icon input-icon-fax"></span><span class="input-text">Fax</span></span>
                                        <input type="text" class="form-control input-lg" placeholder="Your Fax">
                                    </div><!-- End .input-group -->
                                    <div class="input-group xlg-margin">
                                        <span class="input-group-addon"><span
                                                class="input-icon input-icon-company"></span><span
                                                class="input-text">Company&#42;</span></span>
                                        <input type="text" required class="form-control input-lg"
                                               placeholder="Your Company">
                                    </div><!-- End .input-group -->

                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                class="input-icon input-icon-password"></span><span
                                                class="input-text">Password&#42;</span></span>
                                        <input type="password" required class="form-control input-lg"
                                               placeholder="Your Password">
                                    </div><!-- End .input-group -->
                                    <div class="input-group xlg-margin">
                                        <span class="input-group-addon"><span
                                                class="input-icon input-icon-password"></span><span
                                                class="input-text">Password&#42;</span></span>
                                        <input type="password" required class="form-control input-lg"
                                               placeholder="Your Password">
                                    </div><!-- End .input-group -->

                                    <div class="input-group custom-checkbox sm-margin">
                                        <input type="checkbox">
                                        <span class="checbox-container">
                                            <i class="fa fa-check"></i>
                                        </span>
                                        I wish to subscribe to the Venedor newsletter.

                                    </div><!-- End .input-group -->

                                    <div class="input-group custom-checkbox sm-margin">
                                        <input type="checkbox">
                                        <span class="checbox-container">
                                            <i class="fa fa-check"></i>
                                        </span>
                                        My delivery and billing addresses are the same.

                                    </div><!-- End .input-group -->

                                </div><!-- End .col-md-6 -->

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <h2 class="checkout-title">Your Address</h2>

                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                class="input-icon input-icon-address"></span><span
                                                class="input-text">Address 1&#42;</span></span>
                                        <input type="text" class="form-control input-lg" placeholder="Your Address">
                                    </div><!-- End .input-group -->
                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                class="input-icon input-icon-address"></span><span
                                                class="input-text">Address 2&#42;</span></span>
                                        <input type="text" required class="form-control input-lg"
                                               placeholder="Your Address">
                                    </div><!-- End .input-group -->
                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                class="input-icon input-icon-city"></span><span class="input-text">City&#42;</span></span>
                                        <input type="text" required class="form-control input-lg"
                                               placeholder="Your City">
                                    </div><!-- End .input-group -->
                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                class="input-icon input-icon-postcode"></span><span
                                                class="input-text">Post Code&#42;</span></span>
                                        <input type="text" required class="form-control input-lg"
                                               placeholder="Your Post Code">
                                    </div><!-- End .input-group -->
                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                class="input-icon input-icon-country"></span><span
                                                class="input-text">Country*</span></span>
                                        <div class="large-selectbox clearfix">
                                            <select id="country" name="country" class="selectbox">
                                                <option value="United Kingdom">United Kingdom</option>
                                                <option value="Brazil">Brazil</option>
                                                <option value="France">France</option>
                                                <option value="Italy">Italy</option>
                                                <option value="Spain">Spain</option>
                                            </select>
                                        </div><!-- End .large-selectbox-->
                                    </div><!-- End .input-group -->

                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                class="input-icon input-icon-region"></span><span
                                                class="input-text">Region / State&#42;</span></span>
                                        <div class="large-selectbox clearfix">
                                            <select id="state" name="state" class="selectbox">
                                                <option value="California">California</option>
                                                <option value="Texas">Texas</option>
                                                <option value="NewYork">NewYork</option>
                                                <option value="Narnia">Narnia</option>
                                                <option value="Jumanji">Jumanji</option>
                                            </select>
                                        </div><!-- End .large-selectbox-->
                                    </div><!-- End .input-group -->
                                    <div class="input-group custom-checkbox md-margin">
                                        <input type="checkbox">
                                        <span class="checbox-container">
                                            <i class="fa fa-check"></i>
                                        </span>
                                        I have reed and agree to the <a href="#">Privacy Policy</a>.

                                    </div><!-- End .input-group -->
                                    <a href="#" class="btn btn-custom-2">CONTINUE</a>
                                </div><!-- End .col-md-6 -->

                            </div><!-- End .row -->
                        </div><!-- End .panel-body -->
                    </div><!-- End .panel-collapse -->

                </div><!-- End .panel -->
                <div class="panel">
                    <div class="accordion-header">
                        <div class="accordion-title">3 Step: <span>Delivery Details</span></div>
                        <!-- End .accordion-title -->
                        <a class="accordion-btn" data-toggle="collapse" data-parent="#checkout" data-target="#delivery-details"></a>
                    </div><!-- End .accordion-header -->

                    <div id="delivery-details" class="collapse">
                        <div class="panel-body">
                            <p>Details about delivery</p>
                        </div><!-- End .panel-body -->
                    </div><!-- End .panel-collapse -->

                </div><!-- End .panel -->
                <div class="panel">
                    <div class="accordion-header">
                        <div class="accordion-title">4 Step: <span>Delivery Method</span></div>
                        <!-- End .accordion-title -->
                        <a class="accordion-btn" data-toggle="collapse" data-parent="#checkout" data-target="#delivery-method"></a>
                    </div><!-- End .accordion-header -->

                    <div id="delivery-method" class="collapse">
                        <div class="panel-body">
                            <p>Choose your delivery method.</p>
                        </div><!-- End .panel-body -->
                    </div><!-- End .panel-collapse -->

                </div><!-- End .panel -->
                <div class="panel">
                    <div class="accordion-header">
                        <div class="accordion-title">5 Step: <span>Payment Method</span></div>
                        <!-- End .accordion-title -->
                        <a class="accordion-btn" data-toggle="collapse" data-parent="#checkout" data-target="#payment-method"></a>
                    </div><!-- End .accordion-header -->

                    <div id="payment-method" class="collapse">
                        <div class="panel-body">
                            <p>Choose your payment method.</p>
                        </div><!-- End .panel-body -->
                    </div><!-- End .panel-collapse -->

                </div><!-- End .panel -->
                <div class="panel">
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

</script>
<?php common\helpers\ViewHelper::endRegisterScript(); ?>
