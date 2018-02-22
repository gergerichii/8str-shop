<?php

namespace common\modules\catalog\widgets;

use common\modules\catalog\models\ProductRubric;
use common\modules\catalog\Module;
use yii\base\Widget;

/**
 * Class MainSearchWidget
 *
 * @author Andriy Ivanchenko <ivanchenko.andriy@gmail.com>
 */
class MainSearchWidget extends Widget
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        /** @var Module $catalog */
        /** @var ProductRubric $root */
        $root = ProductRubric::find()->roots()->one();
        if (!$root) {
            return [];
        }

        $depth = 1;

        /** @var ProductRubric[] $rubrics */
        $query = $root->children($depth);
        $query->andWhere('visible_on_home_page=1');

        $rubricsOptions = $query->select('name,id')->indexBy('id')->asArray()->column();
        return $this->render('search_form', [
            'rubricsOptions' => ['' => 'Choose a rubric'] + $rubricsOptions
        ]);
    }
}