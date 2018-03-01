<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var array $rubricsOptions
 */
?>

<form id="quick-search-form" class="form-inline quick-search-form" role="form" action="#">
    <div class="form-group">
        <div class="typeahead__container">
            <div class="typeahead__field">
                <span class="typeahead__query">
                    <input id="quick-search-form-query" name="query" type="text" class="form-control" placeholder="Search here" data-provide="typeahead" data-typeahead-source-url="<?= Url::to('/site/search'); ?>" autocomplete="off">
                </span>
            </div>
        </div>

        <select id="quick-search-form-rubric" name="rubric" class="form-control">
            <?= Html::renderSelectOptions('', $rubricsOptions); ?>
        </select>
    </div>

    <button type="submit" id="quick-search" class="btn btn-custom"></button>
</form>
