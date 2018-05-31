<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 17.04.2018
 * Time: 18:41
 */

namespace common\modules\search\widgets;

use common\modules\catalog\models\ProductRubric;
use kartik\widgets\Widget;

class SearchWidget extends Widget {
    public function run() {
        
        $rubrics = $this->getRubricsOptions();
        return $this->render('main-search-form', ['rubrics' => $rubrics]);
    }
    
    /**
     * Get rubrics options
     * @return array
     */
    public function getRubricsOptions() {
        /** @var \common\modules\catalog\CatalogModule $catalog */
        /** @var \common\modules\catalog\models\ProductRubric $root */
        $root = ProductRubric::find()->roots()->one();
        if (!$root) {
            return [];
        }

        $depth = 1;

        /** @var ProductRubric[] $rubrics */
        $query = $root->children($depth);
        $query->andWhere('visible_on_home_page=1');

        return $query->select('name,id')->indexBy('id')->asArray()->column();
    }
    
}