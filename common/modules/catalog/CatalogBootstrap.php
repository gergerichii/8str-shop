<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 03.05.2018
 * Time: 10:11
 */

namespace common\modules\catalog;
use common\modules\baseModule\BaseModuleBootstrap;
use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * Class CatalogBootstrap
 *
 * @package common\modules\catalog
 */
class CatalogBootstrap extends BaseModuleBootstrap {
    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function bootstrap($app) {
        if(!$app->hasModule('catalog')) {
            $app->setModule('catalog', ['class' => CatalogModule::class]);
        }
        if(!$app->hasModule('dynagrid')) {
            $app->setModule('dynagrid', [
                'class' => \kartik\dynagrid\Module::class,
                'dbSettings' => ['tableName' => 'dynagrid'],
                'dbSettingsDtl' => ['tableName' => 'dynagrid_dtl'],
            ]);
        }
        if(!$app->hasModule('gridview')) {
            $app->setModule('gridview', ['class' => \kartik\grid\Module::class]);
        }

        $feRules = [
            [
                'pattern' => 'catalog/<catalogPath:[\w\-\.,/_]*?/[^\d]*$|[\w\-\.,_]+>',
                'route' => '/catalog/default/index',
                'defaults' => [
                    'catalogPath' => ''
                ],
                'encodeParams' => false,
            ],
            [
                'pattern' => 'catalog/<catalogPath:[\w\-\.,/_]+?>/<productId:\d+>',
                'route' => '/catalog/default/product',
                'defaults' => [
                    'catalogPath' => '',
                    'productId' => '',
                ],
                'encodeParams' => false,
            ],
            'catalog/seacrh' => '/catalog/default/search',
            'catalog' => 'catalog/default/index/',
        ];
        $beRules = [
            '/catalog' => '/catalog/admin/default/index',
            '/catalog/<_a:(rubrics|create|delete-image|upload-image)>' => '/catalog/admin/default/<_a>',
            '/catalog/<_a:(view|delete|update)>/<id:\d+>' => '/catalog/admin/default/<_a>',
            '/catalog/crud' => '/catalog/admin/crud/index',
            '/catalog/crud/<_a:(rubrics|create|delete-image|upload-image)>' => '/catalog/admin/crud/<_a>',
            '/catalog/crud/<_a:(view|delete|update)>/<id:\d+>' => '/catalog/admin/crud/<_a>',
            // kartik\grid for export in rbac
            '/gridview/export/download' => '/gridview/export/download',
        ];
        
        self::addUrlRules(self::URL_RULES_TYPE_FRONTEND, $feRules);
        self::addUrlRules(self::URL_RULES_TYPE_BACKEND, $beRules);
    }
}