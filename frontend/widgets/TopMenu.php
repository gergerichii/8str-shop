<?php

namespace frontend\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
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
        /** @var \common\modules\catalog\Module $catalog */
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