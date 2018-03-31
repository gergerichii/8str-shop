<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 30.03.2018
 * Time: 10:44
 */

use common\helpers\StringsHelper;

if (!function_exists('printHR')) {
    function printHR($num = 100) {
        switch ($num) {
            case 0:
                $d0 = ','; $d1 = ','; $d2 = ',';
                break;
            case 1:
                $d0 = '|'; $d1 = '\''; $d2 = '|';
                break;
            case 2:
                $d0 = '|'; $d1 = '-'; $d2 = '|';
                break;
            case 3:
                $d0 = '|'; $d1 = '-'; $d2 = '\'';
                break;
            case 4:
                $d0 = '\''; $d1 = '-'; $d2 = '-';
                break;
            default:
                $d0 = '|'; $d1 = '|'; $d2 = '|';
                break;
        }
        
        echo sprintf("{$d0}-%-'-60s-{$d1}-%-'-15s-{$d1}-%-'-15s-{$d1}-%-'-15s-{$d2}-%-'-15s-{$d0}", '', '', '','', '') . "\n";
    }
}

/** @var \yii\web\View $this */
/** @var \common\modules\order\models\TemporaryOrder $form */

echo Yii::$app->name . "\n\n";
echo 'Благодарим за заказ! В ближайшее время с Вами свяжется наш менеджер для подтверждения заказа' . "\n\n";
$id = $form->id + 32000;
echo "Детали заказа #{$id}:" . "\n";

$formatter = Yii::$app->formatter;
//printHR(0);
$format = "%'.-60s %'.18s %'.18s %'.18s %'.18s";
echo StringsHelper::mb_sprintf($format, 'Наименование товара', 'Код продукта', 'Цена за единицу', 'Количество', 'Сумма') . "\n";
foreach($form->order_data['products'] as $element){
    echo StringsHelper::mb_sprintf(
        $format, substr($element['name'], 0, 60),
        $element['code'],
        $formatter->asCurrency($element['price']),
        $element['count'],
        $formatter->asCurrency($element['cost'])
    ) . "\n";
}

$format = '%\'.-30s %\'.s';
$cost = $formatter->asCurrency($form->order_data['cost']);
$delivery = $formatter->asCurrency($form->delivery_options['methodPrice']);
$allCost = $formatter->asCurrency($form->delivery_options['methodPrice'] + $form->order_data['cost']);

echo StringsHelper::mb_sprintf($format, 'И того по товару:', $cost) . "\n";
echo StringsHelper::mb_sprintf($format, 'Доставка:', $delivery) . "\n";
echo StringsHelper::mb_sprintf($format, 'Полная стоимость заказа:', $allCost) . "\n\n";

echo "Информация по оплате:\n";
echo StringsHelper::mb_sprintf("%'.-20s %'.s", 'Способ оплаты:', $form->payment_method['methodName']) . "\n";
if($form->payment_method['requisites'])
    echo StringsHelper::mb_sprintf("%'.-20s %'.s", 'Реквизиты:', $form->payment_method['requisites']) . "\n";
echo "\nИнформация по доставке:\n";
echo StringsHelper::mb_sprintf("%'.-20s %'.s", 'Метод доставки:', $form->delivery_options['methodName']) . "\n";
echo StringsHelper::mb_sprintf("%'.-20s %'.s", 'Адрес доставки:', $form->delivery_options['address']) . "\n";
echo StringsHelper::mb_sprintf("%'.-20s %'.s", 'Контактное лицо:', $form->delivery_options['contact']) . "\n";
echo StringsHelper::mb_sprintf("%'.-20s %'.s", 'Телефон для связи:', $form->delivery_options['phone']) . "\n";
echo StringsHelper::mb_sprintf("%'.-20s %'.s", 'Электронная почта:', $form->delivery_options['email']) . "\n";
echo StringsHelper::mb_sprintf("%'.-20s %'.s", 'Комментарий:', $form->delivery_options['comment']) . "\n";

echo  "\n\n";

echo 'Наши контакты:'."\n";
foreach (Yii::$app->params['contacts'] as $contact => $value) {
    echo StringsHelper::mb_sprintf('%-10s: %s', $contact, implode(', ', (array)$value)) . "\n";
}
