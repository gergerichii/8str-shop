<?php
/** @var string $directoryAsset */
?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        <?php $mainSiteUrlManager = (isset(\Yii::$app->params['mainSite'])) ? \Yii::$app->params['mainSite'] : 'shop_8str' ?>
        <?php $mainSiteUrlManager = \Yii::$app->get("{$mainSiteUrlManager}UrlManager") ?>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    ['label' => '8str', 'options' => ['class' => 'header']],
                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    [
                        'label' => 'RBAC',
                        'icon' => 'unlock-alt',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Role', 'icon' => 'user', 'url' => ['/rbac/role']],
                            ['label' => 'Permission', 'icon' => 'shield', 'url' => ['/rbac/permission']],
                            ['label' => 'Assignment', 'icon' => 'share-alt', 'url' => ['/rbac/assignment']],
                            ['label' => 'Rule', 'icon' => 'share', 'url' => ['/rbac/rule']],
                        ]
                    ],
                    [
                        'label' => 'Catalog',
                        'icon' => 'list',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Products', 'icon' => 'gift', 'url' => ['/catalog']],
                            ['label' => 'Rubrics', 'icon' => 'folder', 'url' => ['/catalog/admin/default/rubrics']],
                            ['label' => 'Create', 'icon' => 'plus', 'url' => ['/catalog/admin/default/create']],
                        ]
                    ],
                    ['label' => 'Счетчики', 'icon' => 'square' , 'template' => '<a href="' .
                        \yii\helpers\Url::toRoute('/counters/admin/default/index') . '">{icon} {label}</a>'],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    ['label' => 'Site', 'icon' => 'square' , 'template' => '<a href="' . $mainSiteUrlManager->createAbsoluteUrl('/site/index') . '">{icon} {label}</a>'],
                    ['label' => 'Flexbox песочница', 'icon' => 'square' , 'template' => '<a href="' . \yii\helpers\Url::to('/flexbox-playground/dist/') . '">{icon} {label}</a>'],
                ],
            ]
        ); ?>
    </section>

</aside>
