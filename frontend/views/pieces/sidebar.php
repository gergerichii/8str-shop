<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 10.01.2018
 * Time: 19:04
 */

use common\modules\catalog\widgets\ProductTagWidget;
use frontend\widgets\LeftMenu;
use yii\widgets\Menu;

/** @var \common\modules\catalog\Module $catalog */
$catalog = \Yii::$app->getModule('catalog');

?>
<div class="col-md-3 col-sm-4 col-xs-12 sidebar">
    <div class="widget">
        <h3>Категории</h3>

        <?= LeftMenu::widget([
            'options' => [
                'tag' => 'div',
                'class' => 'list-group list-group-brand list-group-accordion'
            ],
            'itemOptions' => ['tag' => false],
            'items' => $catalog->getMenuStructure(1),
            'linkTemplate' => '<a href="{url}" class="list-group-item">{label}<span class="filter-icon filter-icon-{icon}"></span></a>',
        ]); ?>
    </div>

    <div class="widget">
        <h3>Производители</h3>

        <?= LeftMenu::widget([
            'options' => [
                'tag' => 'div',
                'class' => 'list-group list-group-brand jscrollpane'
            ],
            'itemOptions' => ['tag' => false],
            'linkTemplate' => '<a href="{url}" class="list-group-item">{label}</a>',
            'items' => $catalog->getBrandMenuStructure(),
        ]); ?>
    </div>

    <div class="widget popular">
        <h3>Популярные товары</h3>

        <div class="related-slider flexslider sidebarslider">
            <?= ProductTagWidget::widget([
                'limit' => 12,
                'tagName' => 'popular',
                'viewName' => 'sidebarPromo'
            ]); ?>
        </div><!-- End .related-slider -->
    </div>
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

<?php common\helpers\ViewHelper::startRegisterScript($this); ?>
<script>
var menu = $('.list-group-brand.jscrollpane');
if (menu.height() > 330) {
    menu.css('height', 330);
    menu.jScrollPane({
        showArrows: false
    });
}
</script>
<?php common\helpers\ViewHelper::endRegisterScript(); ?>
