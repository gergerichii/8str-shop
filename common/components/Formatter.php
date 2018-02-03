<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 03.02.2018
 * Time: 13:21
 */

namespace common\components;
use NumberFormatter;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;

class Formatter extends \yii\i18n\Formatter
{
    public $currencyShowDecimals = true;
    public $currencySymbol = 'руб.';
    
    /**
     * @var bool whether the [PHP intl extension](http://php.net/manual/en/book.intl.php) is loaded.
     */
    protected $_intlLoaded = false;

    public function init() {
        parent::init();
        $this->_intlLoaded = extension_loaded('intl');
    }
    
    /**
     * @param mixed $value
     * @param null  $currency
     * @param array $options
     * @param array $textOptions
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function asCurrency($value, $currency = NULL, $options = [], $textOptions = []) {
        if ($value === null) {
            return $this->nullDisplay;
        }
        $value = $this->normalizeNumericValue($value);
        
        $decimals = ($this->currencyShowDecimals) ? 2 : 0;

        if ($this->_intlLoaded) {
            $currency = $currency ?: $this->currencyCode;
            // currency code must be set before fraction digits
            // http://php.net/manual/en/numberformatter.formatcurrency.php#114376
            if ($currency && !isset($textOptions[NumberFormatter::CURRENCY_CODE])) {
                $textOptions[NumberFormatter::CURRENCY_CODE] = $currency;
            }
            
            $formatter = $this->createNumberFormatter(NumberFormatter::CURRENCY, $decimals, $options, $textOptions);
            if ($this->currencySymbol) {
                $formatter->setSymbol(NumberFormatter::CURRENCY_SYMBOL, $this->currencySymbol);
                $formatter->setSymbol(NumberFormatter::INTL_CURRENCY_SYMBOL, $this->currencySymbol);
            }
            if ($currency === null) {
                $result = $formatter->format($value);
            } else {
                $result = $formatter->formatCurrency($value, $currency);
            }
            if ($result === false) {
                throw new InvalidParamException('Formatting currency value failed: ' . $formatter->getErrorCode() . ' ' . $formatter->getErrorMessage());
            }

            return $result;
        }

        if ($currency === null) {
            if ($this->currencyCode === null) {
                throw new InvalidConfigException('The default currency code for the formatter is not defined and the php intl extension is not installed which could take the default currency from the locale.');
            }
            $currency = $this->currencySymbol ? $this->currencySymbol : $this->currencyCode;
        }
        
        return $currency . ' ' . $this->asDecimal($value, $decimals, $options, $textOptions);
    }
}