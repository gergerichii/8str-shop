<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE 8]> <html class="ie8" lang="<?= Yii::$app->language ?>"> <![endif]-->
<!--[if IE 9]> <html class="ie9" lang="<?= Yii::$app->language ?>"> <![endif]-->
<!--[if !IE]><!--> <html lang="<?= Yii::$app->language ?>"> <!--<![endif]-->
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php /** TODO: Вынести в модуль SEO */ ?>
    <meta name="description" content="<?=Yii::$app->params['siteDesc']?>">
    <!--[if IE]> <meta http-equiv="X-UA-Compatible" content="IE=edge"> <![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php $this->head() ?>
</head>
<body class="pattern30">
    <?php $this->beginBody() ?>
    <div id="wrapper" class="boxed">
        <?=$this->renderFile('@app/views/layouts/pieces/header.php')?>
        <section id="content">
            <?= Alert::widget() ?>
            <?= !empty($this->params['breadcrumbs'])
                ? $this->renderFile('@app/views/layouts/pieces/breadcrumbs.php') :
                null
            ?>
            <?= $content ?>
        </section><!-- End #content -->
        <?=$this->renderFile('@app/views/layouts/pieces/footer.php')?>
    </div>
    <a href="#" id="scroll-top" title="Scroll to Top"><i class="fa fa-angle-up"></i></a><!-- End #scroll-top -->
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();