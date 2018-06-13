<?php

namespace common\modules\news\widgets;

use common\modules\files\components\FilesManager;
use common\modules\news\models\Article;
use common\modules\news\Module as NewsModule;
use yii\base\Widget;

/**
 * Class LatestNewsWidgets
 */
class LatestNewsWidgets extends Widget
{
    /**
     * @var int $limit Limit
     */
    public $limit = 5;

    /**
     * View name
     * @var string
     */
    public $viewName = 'latestNewsWidget';

    /**
     * @inheritdoc
     */
    public function run() {
        $articles = Article::find()
            ->limit($this->limit)
            ->orderBy('published_at DESC')
            ->all();

        if (!$articles) {
            return '';
        }

        /** @var NewsModule $news */
        $newsModule = \Yii::$app->getModule('news');
        /** @var FilesManager $filesManager */
        $filesManager = \Yii::$app->getModule('files')->manager;

        return $this->render($this->viewName, [
            'articles' => $articles,
            'newsModule' => $newsModule,
            'filesManager' => $filesManager
        ]);
    }
}