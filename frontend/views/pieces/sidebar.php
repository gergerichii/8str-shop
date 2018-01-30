<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 10.01.2018
 * Time: 19:04
 */
?>
<div class="col-md-3 col-sm-4 col-xs-12 sidebar">
    <div class="widget">
        <h3>Категории</h3>
            <?=\yii\widgets\Menu::widget([
                'items' => \Yii::$app->getModule('catalog')->getMenuStructure(1),
            ]);?>
    </div>

    <div class="widget">
        <h3>Categories</h3>

        <div class="list-group list-group-brand list-group-accordion">
            <a href="#" class="list-group-item">Fashion
                <span class="filter-icon filter-icon-fashion"></span>
            </a>
            <a href="#" class="list-group-item">Furniture
                <span class="filter-icon filter-icon-furniture"></span>
            </a>
            <a href="#" class="list-group-item">Electronics
                <span class="filter-icon filter-icon-electronics"></span>
            </a>
            <a href="#" class="list-group-item">Gifts
                <span class="filter-icon filter-icon-gifts"></span>
            </a>
            <a href="#" class="list-group-item">Books
                <span class="filter-icon filter-icon-books"></span>
            </a>
            <a href="#" class="list-group-item">Music
                <span class="filter-icon filter-icon-music"></span>
            </a>
            <a href="#" class="list-group-item">Sport &amp; Tourism
                <span class="filter-icon filter-icon-sport"></span>
            </a>
            <a href="#" class="list-group-item">Games
                <span class="filter-icon filter-icon-games"></span>
            </a>
        </div><!-- End .list-group -->
    </div>

    <div class="widget">
        <h3>Brands</h3>

        <div class="list-group list-group-brand">
            <a href="#" class="list-group-item">Armani</a>
            <a href="#" class="list-group-item">Bulgari</a>
            <a href="#" class="list-group-item">Christion Dior</a>
            <a href="#" class="list-group-item">Dolce &amp; Gabbana</a>
            <a href="#" class="list-group-item">Fendi</a>
            <a href="#" class="list-group-item">Givenchy</a>
            <a href="#" class="list-group-item">Donna Karan</a>
        </div><!-- End .list-group -->
    </div>

    <div class="widget subscribe">
        <h3>BE THE FIRST TO KNOW</h3>
        <p> Get all the latest information on Events, Sales and Offers. Sign up for the Venedor store newsletter today.</p>
        <form action="#" id="subscribe-form">
            <div class="form-group">
                <input type="email" class="form-control" id="subscribe-email" placeholder="Enter your email address">
            </div>
            <input type="submit" value="SUBMIT" class="btn btn-custom">
        </form>
    </div>

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
                        <img src="/images/testimonials/anna.jpg" alt="Computer Ceo">
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
                        <img src="/images/testimonials/jake.jpg" alt="Computer Ceo">
                        <figcaption>
                            <a href="#">Jake Suasoo</a>
                            <span>17.05.2013</span>
                        </figcaption>
                    </figure>
                </li>
            </ul>
        </div><!-- End .testimonials-slider -->
    </div><!-- End .widget -->

    <div class="widget popular">
        <h3>Popular</h3>

        <div class="related-slider flexslider sidebarslider">
            <ul class="related-list clearfix">
                <li>
                    <div class="related-product clearfix">
                        <figure>
                            <img src="/images/products/thumbnails/item1.jpg" alt="item1">
                        </figure>
                        <h5><a href="#">Jacket Suiting Blazer</a></h5>
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-result" data-result="84"></div>
                            </div><!-- End .ratings -->
                        </div><!-- End .rating-container -->
                        <div class="related-price">$40</div><!-- End .related-price -->
                    </div><!-- End .related-product -->

                    <div class="related-product clearfix">
                        <figure>
                            <img src="/images/products/thumbnails/item2.jpg" alt="item2">
                        </figure>
                        <h5><a href="#">Gap Graphic Cuffed</a></h5>
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-result" data-result="84"></div>
                            </div><!-- End .ratings -->
                        </div><!-- End .rating-container -->
                        <div class="related-price">$17</div><!-- End .related-price -->
                    </div><!-- End .related-product -->
                    <div class="related-product clearfix">
                        <figure>
                            <img src="/images/products/thumbnails/item2.jpg" alt="item2">
                        </figure>
                        <h5><a href="#">Gap Graphic Cuffed</a></h5>
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-result" data-result="84"></div>
                            </div><!-- End .ratings -->
                        </div><!-- End .rating-container -->
                        <div class="related-price">18$</div><!-- End .related-price -->
                    </div><!-- End .related-product -->

                    <div class="related-product clearfix">
                        <figure>
                            <img src="/images/products/thumbnails/item3.jpg" alt="item3">
                        </figure>
                        <h5><a href="#">Women's Lauren Dress</a></h5>
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-result" data-result="84"></div>
                            </div><!-- End .ratings -->
                        </div><!-- End .rating-container -->
                        <div class="related-price">$30</div><!-- End .related-price -->
                    </div><!-- End .related-product -->
                </li>
                <li>
                    <div class="related-product clearfix">
                        <figure>
                            <img src="/images/products/thumbnails/item4.jpg" alt="item4">
                        </figure>
                        <h5><a href="#">Swiss Mobile Phone</a></h5>
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-result" data-result="64"></div>
                            </div><!-- End .ratings -->
                        </div><!-- End .rating-container -->
                        <div class="related-price">$39</div><!-- End .related-price -->
                    </div><!-- End .related-product -->

                    <div class="related-product clearfix">
                        <figure>
                            <img src="/images/products/thumbnails/item5.jpg" alt="item5">
                        </figure>
                        <h5><a href="#">Zwinzed HeadPhones</a></h5>
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-result" data-result="94"></div>
                            </div><!-- End .ratings -->
                        </div><!-- End .rating-container -->
                        <div class="related-price">$18.99</div><!-- End .related-price -->
                    </div><!-- End .related-product -->

                    <div class="related-product clearfix">
                        <figure>
                            <img src="/images/products/thumbnails/item1.jpg" alt="item1">
                        </figure>
                        <h5><a href="#">Jacket Suiting Blazer</a></h5>
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-result" data-result="84"></div>
                            </div><!-- End .ratings -->
                        </div><!-- End .rating-container -->
                        <div class="related-price">$40</div><!-- End .related-price -->
                    </div><!-- End .related-product -->

                    <div class="related-product clearfix">
                        <figure>
                            <img src="/images/products/thumbnails/item6.jpg" alt="item6">
                        </figure>
                        <h5><a href="#">Kless Man Suit</a></h5>
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-result" data-result="74"></div>
                            </div><!-- End .ratings -->
                        </div><!-- End .rating-container -->
                        <div class="related-price">$99</div><!-- End .related-price -->
                    </div><!-- End .related-product -->
                </li>
                <li>

                    <div class="related-product clearfix">
                        <figure>
                            <img src="/images/products/thumbnails/item2.jpg" alt="item2">
                        </figure>
                        <h5><a href="#">Gap Graphic Cuffed</a></h5>
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-result" data-result="84"></div>
                            </div><!-- End .ratings -->
                        </div><!-- End .rating-container -->
                        <div class="related-price">$17</div><!-- End .related-price -->
                    </div><!-- End .related-product -->

                    <div class="related-product clearfix">
                        <figure>
                            <img src="/images/products/thumbnails/item4.jpg" alt="item4">
                        </figure>
                        <h5><a href="#">Women's Lauren Dress</a></h5>
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-result" data-result="84"></div>
                            </div><!-- End .ratings -->
                        </div><!-- End .rating-container -->
                        <div class="related-price">$30</div><!-- End .related-price -->
                    </div><!-- End .related-product -->
                </li>
            </ul>
        </div><!-- End .related-slider -->
    </div>

    <div class="widget latest-posts">
        <h3>Recent Posts</h3>

        <div class="latest-posts-slider flexslider sidebarslider">
            <ul class="latest-posts-list clearfix">
                <li>
                    <a href="single.html">
                        <figure class="latest-posts-media-container">
                            <img class="img-responsive" src="/images/blog/post1-small.jpg" alt="lats post">
                        </figure>
                    </a>
                    <h4><a href="single.html">35% Discount on second purchase!</a></h4>
                    <p>Sed blandit nulla nec nunc ullamcorper tristique. Mauris adipiscing cursus ante ultricies dictum sed lobortis.</p>
                    <div class="latest-posts-meta-container clearfix">
                        <div class="pull-left">
                            <a href="#">Read More...</a>
                        </div><!-- End .pull-left -->
                        <div class="pull-right">
                            12.05.2013
                        </div><!-- End .pull-right -->
                    </div><!-- End .latest-posts-meta-container -->
                </li>

                <li>
                    <a href="single.html">
                        <figure class="latest-posts-media-container">
                            <img class="img-responsive" src="/images/blog/post2-small.jpg" alt="lats post">
                        </figure>
                    </a>
                    <h4><a href="single.html">Free shipping for regular customers.</a></h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque fuga officia in molestiae easint..</p>
                    <div class="latest-posts-meta-container clearfix">
                        <div class="pull-left">
                            <a href="#">Read More...</a>
                        </div><!-- End .pull-left -->
                        <div class="pull-right">
                            10.05.2013
                        </div><!-- End .pull-right -->
                    </div><!-- End .latest-posts-meta-container -->
                </li>

                <li>
                    <a href="single.html">
                        <figure class="latest-posts-media-container">
                            <img class="img-responsive" src="/images/blog/post3-small.jpg" alt="lats post">
                        </figure>
                    </a>
                    <h4><a href="#">New jeans on sales!</a></h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque fuga officia in molestiae easint..</p>
                    <div class="latest-posts-meta-container clearfix">
                        <div class="pull-left">
                            <a href="#">Read More...</a>
                        </div><!-- End .pull-left -->
                        <div class="pull-right">
                            8.05.2013
                        </div><!-- End .pull-right -->
                    </div><!-- End .latest-posts-meta-container -->
                </li>

            </ul>
        </div><!-- End .latest-posts-slider -->
    </div><!-- End .widget -->

    <div class="widget banner-slider-container">
        <div class="banner-slider flexslider">
            <ul class="banner-slider-list clearfix">
                <li><a href="#"><img src="/images/banner1.jpg" alt="Banner 1"></a></li>
                <li><a href="#"><img src="/images/banner2.jpg" alt="Banner 2"></a></li>
                <li><a href="#"><img src="/images/banner3.jpg" alt="Banner 3"></a></li>
            </ul>
        </div>
    </div><!-- End .widget -->

</div><!-- End .col-md-3 -->

