<?php

$config = [
    'bootstrap' => [
        'common\modules\counters\CountersBootstrap',
        'log',
        'common\modules\catalog\CatalogBootstrap',
        'cart',
        'common\modules\order\Bootstrap',
        'common\modules\files\FilesBootstrap',
        'common\modules\search\SearchBootstrap',
    ],
    'controllerMap' => [
    ],
    'container' => [
        'definitions' => [
            // common\widgets\Yii2modAlert подгружается к любому выводу кроме Ajax через setUp класс
            'common\widgets\Yii2modAlert' => [
                'options' => [
                    'icon' => 'glyphicon glyphicon-fire',
                ],
                'clientOptions' => [
                    'placement' => [
                        'from' => 'top',
                        'align' => 'center',
                    ],
                    'mouse_over' => 'pause',
                ],
            ],
        ],
    ],
    'components' => [
        'view' => [
            'as countersBehaviour' => \common\modules\counters\behaviours\CountersViewBehaviour::class,
            'theme' => [
                'pathMap' => [
                    '@common/modules' => '@app/views/modules',
                    '@common/widgets' => '@app/views/widgets',
                    '@vendor' => '@app/views/vendor',
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'traits' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@vendor/cinghie/yii2-traits/messages',
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\entities\User',
            'enableAutoLogin' => true,
        ],
        'session' => [
            // this is the name of the session cookie used for login on the all sites
            'name' => 'sess8str',
        ],
        'request' => [
            'csrfParam' => '_csrf-8str',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'enableStrictParsing' => true,
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                'collapseSlashes' => true,
                'normalizeTrailingSlash' => true,
            ],
            'rules' => [
                '/' => 'site/index',
                '/login' => '/site/login',
                '/logout' => '/site/logout',
                '/signup' => '/site/signup',
                '/error' => '/site/error',
                '/request-password-reset' => '/site/request-password-reset',
                '/reset-password' => '/site/reset-password',
                ['pattern' => 'market', 'route' => 'YandexMarketYml/default/index', 'suffix' => '.yml'],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'modules' => [
        'search' => [
            'class' => 'common\modules\search\SearchModule',
        ],
        'counters' => [
            'class' => 'common\modules\counters\CountersModule',
        ],
        'catalog' => [
            'class' => 'common\modules\catalog\CatalogModule',
        ],
        'files' => [
        ],
        'cart' => [
            'class' => 'common\modules\cart\Module',
        ],
        'order' => [
            'class' => 'common\modules\order\Module',
        ],
        'rbac' => [
            'class' => 'common\modules\rbac\Module',
        ],
        'treemanager' => [
            'class' => '\kartik\tree\Module',
            'treeStructure' => [
                'treeAttribute' => 'tree',
                'leftAttribute' => 'left_key',
                'rightAttribute' => 'right_key',
                'depthAttribute' => 'level',
            ]
        ],
        'YandexMarketYml' => [
            'class' => 'corpsepk\yml\YandexMarketYml',
            'enableGzip' => true, // default is false
            'cacheExpire' => 1, // 1 second. Default is 24 hours
            'categoryModel' => 'common\modules\catalog\models\ProductRubric',
            'shopOptions' => [
                'name' => 'shop8str',
                'company' => 'Интернет магазин Восьмой Страж',
                'url' => 'https://8str.ru',
                'currencies' => [
                    [
                        'id' => 'RUR',
                        'rate' => 1
                    ]
                ],
            ],
            'offerModels' => [
                ['class' => 'common\modules\catalog\models\Product'],
            ],
            'viewPath' => '@common/views/market_yml/',
        ],
        
        'news' => '\common\modules\news\Module',
//        'articles' => [
//            'class' => 'cinghie\articles\Articles',
//            'userClass' => \common\models\entities\User::class,
//
//            // Select Languages allowed
//            'languages' => [
//                "ru-RU" => "ru-RU",
//            ],
//
//            // Select Date Format
//            'dateFormat' => 'd F Y',
//
//            // Select Editor: no-editor, ckeditor, imperavi, tinymce, markdown
//            'editor' => 'ckeditor',
//
//            // Select Path To Upload Category Image
//            'categoryImagePath' => '@webroot/img/articles/categories/',
//            // Select URL To Upload Category Image
//            'categoryImageURL'  => '@web/img/articles/categories/',
//            // Select Path To Upload Category Thumb
//            'categoryThumbPath' => '@webroot/img/articles/categories/thumb/',
//            // Select URL To Upload Category Image
//            'categoryThumbURL'  => '@web/img/articles/categories/thumb/',
//
//            // Select Path To Upload Item Image
//            'itemImagePath' => '@webroot/img/articles/items/',
//            // Select URL To Upload Item Image
//            'itemImageURL' => '@web/img/articles/items/',
//            // Select Path To Upload Item Thumb
//            'itemThumbPath' => '@webroot/img/articles/items/thumb/',
//            // Select URL To Upload Item Thumb
//            'itemThumbURL' => '@web/img/articles/items/thumb/',
//
//            // Select Path To Upload Attachments
//            'attachPath' => '@webroot/attachments/',
//            // Select URL To Upload Attachment
//            'attachURL' => '@web/img/articles/items/',
//            // Select Image Types allowed
//            'attachType' => ['jpg','jpeg','gif','png','csv','pdf','txt','doc','docs'],
//
//            // Select Image Name: categoryname, original, casual
//            'imageNameType' => 'categoryname',
//            // Select Image Types allowed
//            'imageType' => ['png','jpg','jpeg'],
//            // Thumbnails Options
//            'thumbOptions'  => [
//                'small'  => ['quality' => 100, 'width' => 150, 'height' => 100],
//                'medium' => ['quality' => 100, 'width' => 200, 'height' => 150],
//                'large'  => ['quality' => 100, 'width' => 300, 'height' => 250],
//                'extra'  => ['quality' => 100, 'width' => 400, 'height' => 350],
//            ],
//
//            // Slugify Options
//            'slugifyOptions' => [
//                'separator' => '-',
//                'lowercase' => true,
//                'trim' => true,
//                'rulesets'  => [
//                    'default'
//                ]
//            ],
//
//            // Show Titles in the views
//            'showTitles' => true,
//        ],
    ],
];

if (!YII_ENV_TEST) {
    $allowedIPs = ['127.0.0.1', '::1', '192.168.10.1']; // регулируйте в соответствии со своими нуждами

    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => $allowedIPs
    ];
}

return $config;