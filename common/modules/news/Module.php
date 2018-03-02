<?php

namespace common\modules\news;

use common\modules\news\models\Article;
use \yii\base\Module as BaseModule;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/**
 * News module definition class
 */
class Module extends BaseModule
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'common\modules\news\controllers';

    /**
     * Get article uri
     * @param Article $article
     * @return string
     */
    public function getArticleUri(Article $article) {
        return Url::to(['/news/default/index', '#' => $article->alias]);
    }

    /**
     * Get provider of articles
     * @return ActiveDataProvider
     */
    public function getProviderOfArticles() {
        $query = Article::find()->orderBy('published_at DESC');
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                // TODO Replace to configuration
                'defaultPageSize' => 5
            ]
        ]);

        return $provider;
    }
}
