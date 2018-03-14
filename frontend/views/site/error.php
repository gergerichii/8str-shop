<?php

/* @var $this yii\web\View */
/* @var $exception Exception */

use yii\helpers\Html;


?>
<div class="lg-margin"></div><!-- Space -->
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="no-content-comment">
                   <h2><?=Yii::$app->response->statusCode?></h2>
                   <h3><?=$exception->getMessage()?></h3>
               </div><!-- End .no-content-comment -->
		</div><!-- End .col-md-12 -->
	</div><!-- End .row -->
</div><!-- End .container -->

<?php \common\helpers\ViewHelper::startRegisterScript($this); ?>
<script>
	$(function() {

        // Simple Animation for 404 text

            var container = $('.no-content-comment'),
                title = container.find('h2'),
                titleText = title.text(),
                message = container.find('h3'),
                messageText = message.text(),
                titleTextLen = titleText.length,
                messageTextLen = messageText.length,
                titleNew = '',
                messageNew = '',
                time = 50;

           
           function iterate(len, text, newVar, target) {
                for (var i=0 ; i < len; i++) {
                    if (text[i] == '!') { // ! important for line breaks
                        newVar += '<span>'+text[i]+'<br></span>'
                    } else {
                        newVar += '<span>'+text[i]+'</span>';
                    }
               }
               target.html(newVar);
           }


           iterate(titleTextLen, titleText, titleNew, title);

           iterate(messageTextLen, messageText, messageNew, message);


        $(window).on('load', function () {
            
            container.find('span').each(function () {
                time +=80;
                $(this).delay(200).animate({opacity: 1}, time);
            });
            
        });
        
	
	});
</script>
<?php \common\helpers\ViewHelper::endRegisterScript(); ?>
