<?php

namespace frontend\widgets;

use yii\widgets\Menu;

/**
 * Class TopMenu
 */
class TopMenu extends Menu
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        /** @var \common\modules\catalog\CatalogModule $catalog */
        $catalog = \Yii::$app->getModule('catalog');
        $items = $catalog->getMenuStructure(2);
        if (!$items) {
            return '';
        }

        return $this->render('topMenuWidget', [
            'items' => $items
        ]);
    }
}