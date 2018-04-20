<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 18.04.2018
 * Time: 15:34
 */

?>

<?php \common\helpers\ViewHelper::startRegisterScript($this, \yii\web\View::POS_READY); ?>
<script>
    var prepareMainSearchRequest = function (query, settings) {
        var rubric = arguments.callee.rubric;
        settings.url += '?q=' + query;
        if (parseInt(prepareMainSearchRequest.rubric) !== 0) {
            settings.url += '&r=' + prepareMainSearchRequest.rubric;
        }
        return settings;
    };
    prepareMainSearchRequest.rubric = 0;
    
    var changeMainSearchRubric = function (event) {
        var val = $(event.target).val();
        prepareMainSearchRequest.rubric = val ? val : 0;
        
        search_keywords_data_1.initialize();
        val = $('#search-keywords').typeahead('val');
        $('#search-keywords').typeahead('val', '');
        $('#search-keywords').typeahead('val', val);
    };
</script>
<?php \common\helpers\ViewHelper::endRegisterScript(); ?>

<?php $form = \kartik\widgets\ActiveForm::begin([
    'action' => \yii\helpers\Url::toRoute('/catalog/default/index'),
    'method' => 'GET'
]) ?>

<div class="col-12">
    <div class="main-search-left">
        <?=\kartik\widgets\Select2::widget([
            'name' => 'sc',
            'data' => $rubrics,
            'changeOnReset' => true,
            'value' => Yii::$app->request->get('sc'),
            'pluginLoading' => false,
            'options' => [
                'placeholder' => 'Везде',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'dropdownAutoWidth' => true,
            ],
            'pluginEvents' => [
                'change' => new \yii\web\JsExpression('changeMainSearchRubric'),
            ]
        ])?>
    </div>
    <div class="main-search-right">
        <button type="submit" id="quick-search" class="btn btn-custom"></button>
    </div>
    <div class="main-search-fill">
        <?= \kartik\widgets\Typeahead::widget([
            'name' => 'sk',
            'id' => 'search-keywords',
            'hashVarLoadPosition' => \yii\web\View::POS_END,
            'value' => Yii::$app->request->get('sk'),
            'options' => [
                'placeholder' => 'Поиск по каталогу...',
                'dir' => 'auto',
                'tabindex' => 19,
                'class' => 'search-input',
                'autocomplete' => 'off',
            ],
            'dataset' => [
                [
                    'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('name')",
                    'queryTokenizer' => "Bloodhound.tokenizers.obj.whitespace",
                    'display' => 'name',
                    'limit' => 12,
                    'remote' => [
                        'url' => \yii\helpers\Url::toRoute('/search/default/index'),
                        'prepare' => new \yii\web\JsExpression('prepareMainSearchRequest'),
                        'transform' => new \yii\web\JsExpression('transform1'),
                    ],
                    'templates' => [
                        'header' => '<h4 class="league-name">Продукты</h4>'
                    ],
                ],
                [
                    'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('name')",
                    'queryTokenizer' => "Bloodhound.tokenizers.obj.whitespace",
                    'display' => 'name',
                    'remote' => [
                        'url' => \yii\helpers\Url::toRoute('/search/default/index'),
                        'prepare' => new \yii\web\JsExpression('prepareMainSearchRequest'),
                        'transform' => new \yii\web\JsExpression('transform2'),
                    ],
                    'templates' => [
                        'header' => '<h4 class="league-name">Брэрды</h4>'
                    ],
                ],
                [
                    'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('name')",
                    'queryTokenizer' => "Bloodhound.tokenizers.obj.whitespace",
                    'display' => 'name',
                    'remote' => [
                        'url' => \yii\helpers\Url::toRoute('/search/default/index'),
                        'prepare' => new \yii\web\JsExpression('prepareMainSearchRequest'),
                        'transform' => new \yii\web\JsExpression('transform3'),
                    ],
                    'templates' => [
                        'header' => '<h4 class="league-name">Рубрики</h4>'
                    ],
                ],
            ],
            'pluginOptions' => [
                'highlight' => true,
                'hint' => true,
                'menu' => new \yii\web\JsExpression("\$('#main-search-tt-menu.tt-menu')"),
                'minLength' => 2,
            ],
            'container' => ['class' => 'main-search-field'],
        ])?>
    </div>
    <div id="main-search-tt-menu" class="tt-menu tt-empty"></div>
</div>

<?php \kartik\widgets\ActiveForm::end(); ?>

<?php \common\helpers\ViewHelper::startRegisterScript($this); ?>
<script>
    function transform1(suggestions) {
        return suggestions.products;
    }
    function transform2(suggestions) {
        return suggestions.brands;
    }
    function transform3(suggestions) {
        return suggestions.rubrics;
    }
</script>
<?php \common\helpers\ViewHelper::endRegisterScript(); ?>
