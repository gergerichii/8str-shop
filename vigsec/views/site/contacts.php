<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 06.04.2018
 * Time: 16:02
 */

use yii\web\View;

/** @var View $this */
$this->title = 'Контакты';
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row slider-position">
                
                <?=$this->render('@app/views/pieces/sidebar');?>

                <div class="col-md-9 col-sm-8 col-xs-12 main-content ">
                    <header class="content-title">
                        <h1 class="title"><?=$this->title?></h1>
                        <div class="md-margin"></div><!-- space -->
                    </header>
                    <h3>Телефон:</h3>
                    <ul>
                        <li>
                            <?=\Yii::$app->params['contacts']['Телефоны'][0]?>
                        </li>
                    </ul>

                    <div class="md-margin"></div><!-- space -->

                    <h3>Почта:</h3>
                    <ul>
                        <li>
                            <?=\Yii::$app->params['contacts']['email']?>
                        </li>
                    </ul>
                    </p>
                    <p>
                    <h3>Офис в Ярославле:</h3>
                    <p>
                        <?=\Yii::$app->params['contacts']['Адрес']?>
                    </p>
                    </p>
                </div><!-- End .col-md-9 -->
            </div><!-- End .row -->
        </div><!-- End .col-md-12 -->
    </div><!-- End .row -->
</div><!-- End .container -->
