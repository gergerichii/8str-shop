<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 16.01.2018
 * Time: 10:48
 */

namespace common\models\entities;
use yii\base\Model;
use yii\i18n\Formatter;

/**
 *
 * @property int $value
 */
class ProductPriceDummy extends Model implements ProductPriceInterface {

    public $value = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'number'],
        ];
    }

    /**
     * @return string
     */
    public function __toString(){
        return (new Formatter())->asDecimal($this->value, 0) . ' руб.';
    }

    public
    function getValue(){
        return $this->value;
    }
}