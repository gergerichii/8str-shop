<?php

namespace common\modules\catalog\widgets;

use common\modules\catalog\models\forms\ProductFilterForm;
use common\modules\catalog\Module;
use yii\base\Widget;

/**
 * Class ProductFilterWidget
 */
class ProductFilterWidget extends Widget
{
    public $viewName = 'productFilterWidget';

    /** @var ProductFilterForm */
    public $filterForm;

    /**
     * @return string
     */
    public function run() {
        $rubrics = $this->filterForm->getRubrics();
        $brands = $this->filterForm->getBrands();
        /** @var Module $catalog */
        $catalogModule = \Yii::$app->getModule('catalog');

        $prices = $this->filterForm->getPriceRange();

        return $this->render($this->viewName, [
            'rubrics' => $rubrics,
            'brands' => $brands,
            'catalogModule' => $catalogModule,
            'filterForm' => $this->filterForm,
            'priceStartMin' => $prices['start']['min'],
            'priceStartMax' => $prices['start']['max'],
            'priceRangeMin' => $prices['range']['min'],
            'priceRangeMax' => $prices['range']['max'],
        ]);
    }
}