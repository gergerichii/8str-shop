<?php

namespace common\traits;

use unclead\multipleinput\MultipleInput;
use yii\base\Model;

/**
 * Trait ErrorsForMultipleInput
 *
 * @see MultipleInput
 */
trait ErrorsForMultipleInput
{
    /**
     * Add errors from model to multiple input inside activeform
     *
     * @param string $attribute
     * @param int $index
     * @param array $data
     * @param Model $model
     */
    private function addMultipleError(string $attribute, int $index, array $data, Model $model) {
        foreach ($data as $columnName => $columnValue) {
            $key = $attribute . '[' . $index . '][' . $columnName . ']';
            if ($errorMessage = $model->getFirstError($columnName)) {
                $this->addError($key, $errorMessage);
            }
        }
    }
}