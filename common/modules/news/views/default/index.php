<?php
/**
 * @var \yii\data\ActiveDataProvider      $provider
 * @var \common\modules\news\Module       $newsModule
 * @var \common\modules\files\components\FilesManager $filesManager
 */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\widgets\LinkPager;

$formatter = Yii::$app->getFormatter();
/** @var \common\modules\news\models\Article[] $articles */
$articles = $provider->getModels();
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <header class="content-title">
                <h1 class="title">News</h1>
                <p class="title-desc">Do not miss our news. Follow the trends.</p>
            </header>
            <div class="xs-margin"></div><!-- space -->
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 articles-container">
                    <?php foreach ($articles as $article) { ?>
                        <?php $title = Html::encode($article->title); ?>
                        <article class="article" data-hash="#<?= $article->alias; ?>">
                            <div class="article-meta-date">
                                <span><?= $formatter->asDate($article->published_at, 'php:d'); ?></span>
                                <?= $formatter->asDate($article->published_at, 'php:M'); ?>
                            </div><!-- End .article-meta-date -->

                            <figure class="article-media-container">
                                <img src="<?= $filesManager->getFileUri('news/images/detail', $article->image); ?>" alt="<?= $title; ?>">
                            </figure>

                            <h2>
                                <a href="<?= '#'; // $newsModule->getArticleUri($article); ?>"><?= $title; ?></a>
                            </h2>

                            <?php
                            // TODO
                            /*<div class="article-meta-container clearfix">
                                <div class="article-meta-more">
                                    <a href="#"><span class="separator"><i class="fa fa-user"></i></span>By Admin</a>
                                    <a href="#"><span class="separator"><i class="fa fa-comments "></i></span>3 Comments</a>
                                    <a href="#"><span class="separator"><i class="fa fa-tag"></i></span>Category</a>
                                </div><!-- End. pull-left -->

                                <div class="article-meta-view">
                                    <a href="#"><span class="separator"><i class="fa fa-eye "></i></span>151</a>
                                    <a href="#"><span class="separator"><i class="fa fa-heart"></i></span>87</a>
                                </div><!-- End. pull-right -->
                            </div><!-- End .article-meta-container -->*/ ?>

                            <div class="article-content-container">
                                <?= HtmlPurifier::process($article->fulltext); ?>
                            </div><!-- End .article-content-container -->
                        </article><!-- End .article -->
                    <?php } ?>

                    <div class="pagination-container clearfix">
                        <div class="pull-left page-count">
                        </div><!-- End .pull-left -->

                        <div class="pull-right">
                            <?= LinkPager::widget([
                                'pagination' => $provider->getPagination(),
                            ]); ?>
                        </div><!-- End .pull-right -->
                    </div><!-- End pagination-container -->

                    <?php /*<div class="pagination-container clearfix">
                        <div class="pull-left page-count">
                            Page 1 of 5
                        </div><!-- End .pull-left -->

                        <div class="pull-right">
                            <ul class="pagination">
                                <li class="active"><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
                                <li><a href="#"><i class="fa fa-angle-right"></i></a></li>
                            </ul>
                        </div><!-- End .pull-right -->
                    </div><!-- End pagination-container -->
 */ ?>
                </div><!-- End .col-md-9 -->

                <?php /*<aside class="col-md-3 col-sm-4 col-xs-12 sidebar">

                    <div class="widget category-accordion">
                        <h3>Category</h3>
                        <div class="panel-group" id="accordion">
                            <div class="panel panel-custom">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        ELECTRONICS
                                        <a data-toggle="collapse" href="#collapseOne">
                                            <span class="icon-box">&plus;</span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <ul class="category-accordion-list">
                                            <li><a href="">Mobile</a></li>
                                            <li><a href="">Communicators</a></li>
                                            <li><a href="">CMDA Phones</a></li>
                                            <li><a href="">Accessories</a></li>
                                            <li><a href="">Accessories</a></li>
                                            <li><a href="">Memory Cards</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- End .panel -->

                            <div class="panel panel-custom">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        FASHION
                                        <a data-toggle="collapse" href="#collapseTwo">
                                            <span class="icon-box">&minus;</span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="category-accordion-list">
                                            <li><a href="#">Jeans</a></li>
                                            <li><a href="#">Socks</a></li>
                                            <li><a href="">Skirts</a></li>
                                            <li><a href="">Shoes</a></li>
                                            <li><a href="">T-Shirts</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- End .panel -->

                            <div class="panel panel-custom">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        AT HOME
                                        <a data-toggle="collapse" href="#collapseThree">
                                            <span class="icon-box">&minus;</span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="category-accordion-list">
                                            <li><a href="">Tables</a></li>
                                            <li><a href="">Sofas</a></li>
                                            <li><a href="">Carpets</a></li>
                                            <li><a href="">Beds</a></li>
                                            <li><a href="">Chairs</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- End .panel -->

                            <div class="panel panel-custom">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        BOOKS
                                        <a data-toggle="collapse" href="#collapseFour">
                                            <span class="icon-box">&minus;</span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFour" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="category-accordion-list">
                                            <li><a href="">Poems</a></li>
                                            <li><a href="">Novels</a></li>
                                            <li><a href="">Science Fiction</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- End .panel -->
                        </div><!-- End .panel-group -->
                    </div><!-- End .widget -->

                    <div class="widget recent-posts">
                        <h3>Recent Posts</h3>

                        <div class="recent-posts-slider flexslider sidebarslider">
                            <ul class="recent-posts-list clearfix">
                                <li>
                                    <a href="single.html">
                                        <figure class="recent-posts-media-container">
                                            <img src="images/blog/post1-small.jpg" class="img-responsive" alt="lats post">
                                        </figure>
                                    </a>
                                    <h4><a href="single.html">35% Discount on second purchase!</a></h4>
                                    <p>Sed blandit nulla nec nunc ullamcorper tristique. Mauris adipiscing cursus ante ultricies dictum sed lobortis.</p>
                                    <div class="recent-posts-meta-container clearfix">
                                        <div class="pull-left">
                                            <a href="#">Read More...</a>
                                        </div><!-- End .pull-left -->
                                        <div class="pull-right">
                                            12.05.2013
                                        </div><!-- End .pull-right -->
                                    </div><!-- End .recent-posts-meta-container -->
                                </li>

                                <li>
                                    <a href="single.html">
                                        <figure class="recent-posts-media-container">
                                            <img src="images/blog/post2-small.jpg" class="img-responsive" alt="lats post">
                                        </figure>
                                    </a>
                                    <h4><a href="single.html">Free shipping for regular customers.</a></h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque fuga officia in molestiae easint..</p>
                                    <div class="recent-posts-meta-container clearfix">
                                        <div class="pull-left">
                                            <a href="#">Read More...</a>
                                        </div><!-- End .pull-left -->
                                        <div class="pull-right">
                                            10.05.2013
                                        </div><!-- End .pull-right -->
                                    </div><!-- End .recent-posts-meta-container -->
                                </li>

                                <li>
                                    <a href="single.html">
                                        <figure class="recent-posts-media-container">
                                            <img src="images/blog/post3-small.jpg" class="img-responsive" alt="lats post">
                                        </figure>
                                    </a>
                                    <h4><a href="#">New jeans on sales!</a></h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque fuga officia in molestiae easint..</p>
                                    <div class="recent-posts-meta-container clearfix">
                                        <div class="pull-left">
                                            <a href="#">Read More...</a>
                                        </div><!-- End .pull-left -->
                                        <div class="pull-right">
                                            8.05.2013
                                        </div><!-- End .pull-right -->
                                    </div><!-- End .recent-posts-meta-container -->
                                </li>

                            </ul>
                        </div><!-- End .recent-posts-slider -->
                    </div><!-- End .widget -->

                    <div class="widget testimonials">
                        <h3>Testimonials</h3>

                        <div class="testimonials-slider flexslider sidebarslider">
                            <ul class="testimonials-list clearfix">
                                <li>
                                    <div class="testimonial-details">
                                        <header>Best Service!</header>
                                        Maecenas semper aliquam massa. Praesent pharetra sem vitae nisi eleifend molestie. Aliquam molestie scelerisque ultricies. Suspendisse potenti.
                                    </div><!-- End .testimonial-details -->
                                    <figure class="clearfix">
                                        <img src="images/testimonials/anna.jpg" alt="Computer Ceo">
                                        <figcaption>
                                            <a href="#">Anna Retallic</a>
                                            <span>12.05.2013</span>
                                        </figcaption>
                                    </figure>
                                </li>
                                <li>
                                    <div class="testimonial-details">
                                        <header>Cool Style!</header>
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt iure quisquam necessitatibus fugit! Nisi tempora reiciendis omnis error sapiente ipsam maiores dolorem maxime.
                                    </div><!-- End .testimonial-details -->
                                    <figure class="clearfix">
                                        <img src="images/testimonials/jake.jpg" alt="Computer Ceo">
                                        <figcaption>
                                            <a href="#">Jake Suasoo</a>
                                            <span>17.05.2013</span>
                                        </figcaption>
                                    </figure>
                                </li>
                            </ul>
                        </div><!-- End .testimonials-slider -->
                    </div><!-- End .widget -->

                    <div class="widget tags">
                        <h3>Tag Cloud</h3>
                        <ul class="tags-list">
                            <li><a href="#">Category</a></li>
                            <li><a href="#">Phones</a></li>
                            <li><a href="#">Camera</a></li>
                            <li><a href="#">Charges</a></li>
                            <li><a href="#">Accessories</a></li>
                            <li><a href="#">Memory</a></li>
                            <li><a href="#">Communicators</a></li>
                            <li><a href="#">Players</a></li>
                            <li><a href="#">Tablets</a></li>
                            <li><a href="#">Laptops</a></li>
                            <li><a href="#">Consoles</a></li>
                            <li><a href="#">Games</a></li>
                        </ul>
                    </div><!-- End .widget -->

                    <div class="widget flickr-feed">
                        <h3>Flickr</h3>
                        <ul class="flickr-feed-list clearfix">

                        </ul>
                    </div><!-- End .widget -->
                </aside><!-- End .col-md-3 -->
*/ ?>

            </div><!-- End .row -->

        </div><!-- End .col-md-12 -->
    </div><!-- End .row -->
</div><!-- End .container -->
