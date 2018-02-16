<?php
/* @var $this yii\web\View */

/* @var $step2form \common\modules\order\forms\frontend\Step2Form */

?>

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
        <a href="#" class="btn btn-custom-2" role="button" data-action="next-step">Продолжить</a>
    </div><!-- End .col-md-6 -->

</div><!-- End .row -->
