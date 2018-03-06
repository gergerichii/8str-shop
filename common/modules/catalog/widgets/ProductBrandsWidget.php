<?php

namespace common\modules\catalog\widgets;

use common\modules\catalog\models\ProductBrand;
use common\modules\catalog\Module;
use common\modules\files\Module as FilesModule;
use yii\base\Widget;

/**
 * Class ProductBrandsWidget
 */
class ProductBrandsWidget extends Widget
{
    /**
     * View name
     *
     * @var string
     */
    public $viewName = 'productBrandsWidget';

    /**
     * @inheritdoc
     */
    public function run() {
        $brands = ProductBrand::find()->all();
        if (!$brands) {
            return '';
        }

        /** @var Module $catalogModule */
        $catalogModule = \Yii::$app->getModule('catalog');
        /** @var FilesModule $filesModule */
        $filesModule = \Yii::$app->getModule('files');

        return $this->render($this->viewName, [
            'brands' => $brands,
            'catalogModule' => $catalogModule,
            'filesModule' => $filesModule
        ]);
    }
}