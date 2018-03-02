<?php

use yii\helpers\Html;

/**
 * @var \common\modules\news\models\Article[] $articles
 * @var \common\modules\news\Module $newsModule
 * @var \common\modules\files\Module $filesModule
 */

$formatter = Yii::$app->getFormatter();
$image = $filesModule->getEntityInstance('news/images');
?>

<div id="latestnews-slider-container" class="carousel-wrapper">
    <header class="content-title">
        <div class="title-bg">
            <h2 class="title">Latest News</h2>
        </div><!-- End .title-bg -->
    </header>

    <div class="carousel-controls">
        <div id="latestnews-slider-prev" class="carousel-btn carousel-btn-prev">
        </div><!-- End .carousel-prev -->
        <div id="latestnews-slider-next" class="carousel-btn carousel-btn-next carousel-space">
        </div><!-- End .carousel-next -->
    </div><!-- End .carousel-controllers -->

    <div class="sm-margin"></div><!-- space -->

    <div class="row">
        <ul class="latestnews-slider owl-carousel">
            <?php foreach ($articles as $article) { ?>
                <?php $url = $newsModule->getArticleUri($article); ?>
                <li>
                    <a href="<?= $url; ?>">
                        <figure class="latestnews-media-container">
                            <img src="<?= $filesModule->getFileUri('news/images/preview', $article->image); ?>" alt="lats post" class="img-responsive">
                        </figure>
                    </a>

                    <h3>
                        <a href="<?= $url; ?>"><?= Html::encode($article->title); ?></a>
                    </h3>
                    <p><?= Html::encode($article->introtext); ?></p>
                    <div class="latestnews-meta-container clearfix">
                        <div class="pull-left">
                            <a href="<?= $url; ?>">Читать дальше...</a>
                        </div><!-- End .pull-left -->
                        <div class="pull-right">
                            <?= $formatter->asDate($article->published_at); ?>
                        </div><!-- End .pull-right -->
                    </div><!-- End .latest-posts-meta-container -->
                </li>
            <?php } ?>
        </ul>
    </div><!-- End .row -->
</div><!-- End .latestnews-slider-container -->