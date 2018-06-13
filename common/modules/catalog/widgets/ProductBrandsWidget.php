<?php

namespace common\modules\catalog\widgets;

use common\modules\catalog\models\ProductBrand;
use common\modules\catalog\CatalogModule;
use common\modules\files\components\FilesManager;
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

        /** @var CatalogModule $catalogModule */
        $catalogModule = \Yii::$app->getModule('catalog');
        /** @var FilesManager $filesModule */
        $filesModule = \Yii::$app->getModule('files')->manager;

        return $this->render($this->viewName, [
            'brands' => $brands,
            'catalogModule' => $catalogModule,
            'filesModule' => $filesModule
        ]);
    }
}