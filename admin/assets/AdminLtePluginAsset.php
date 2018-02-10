<?php

namespace admin\assets;

use yii\web\AssetBundle;

/**
 * Example asset for AdminLte plugin
 * 
 * @author Andriy Ivanchenko <ivanchenko.andriy@gmail.com>
 */
class AdminLtePluginAsset extends AssetBundle {

    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins';
    public $js = [
        'datatables/dataTables.bootstrap.min.js',
            // more plugin Js here
    ];
    public $css = [
        'datatables/dataTables.bootstrap.css',
            // more plugin CSS here
    ];
    public $depends = [
        'dmstr\web\AdminLteAsset',
    ];

}
