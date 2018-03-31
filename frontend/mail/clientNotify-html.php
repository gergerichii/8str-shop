<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 29.03.2018
 * Time: 13:46
 */

use common\helpers\StringsHelper;

/** @var \yii\web\View $this */
/** @var \common\modules\order\models\TemporaryOrder $form */
/** @var \yii\swiftmailer\Message $message */

$formatter = Yii::$app->formatter;
?>
<header class="content-title">
    <h1 class="title"><?=Yii::$app->name?></h1>
    <p class="title-desc">Благодарим за заказ! В ближайшее время с Вами свяжется наш менеджер для подтверждения заказа</p>
    <p class="title-desc"></p>
    <p class="title-desc">Детали заказа #<?=$form->id + 32000?>:</p>
</header>
<table class="table checkout-table">
    <thead>
    <tr>
        <th class="table-title">Наименование товара</th>
        <th class="table-title">Код продукта</th>
        <th class="table-title">Цена за единицу</th>
        <th class="table-title">Количество</th>
        <th class="table-title">Сумма</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($form->order_data['products'] as $element): ?>
        <tr>
            <td class="item-name-col">
                <figure>
                    <?php $options = ['fileName' => $element['imageName'], 'contentType' => $element['imageMIME']]; ?>
                    <img src="<?=$message->embedContent(base64_decode($element['image']), $options)?>" alt="<?=$element['name']?>">
                </figure>
                <header class="item-name"><?=$element['name']?></header>
            </td>
            <td class="item-code"><?=$element['code']?></td>
            <td class="item-price-col">
                <span class="item-price-special"><?=$formatter->asCurrency($element['price'])?></span>
            </td>
            <td>
                <div class="custom-quantity-input">
                    <?=$element['count']?>
                </div>
            </td>
            <td class="item-total-col">
                <span class="item-price-special"><?=$formatter->asCurrency($element['cost'])?></span>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td class="checkout-table-title" colspan="4">И того по товару:</td>
        <td class="checkout-table-price"><?=$formatter->asCurrency($form->order_data['cost'])?></td>
    </tr>
    <tr>
        <td class="checkout-table-title" colspan="4">Доставка:</td>
        <td class="checkout-table-price">
            <?=$formatter->asCurrency($form->delivery_options['methodPrice'])?>
        </td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
        <td class="checkout-total-title" colspan="4"><strong>Всего по заказу:</strong></td>
        <td class="checkout-total-price cart-total">
            <strong>
                <?=$formatter->asCurrency($form->delivery_options['methodPrice'] + $form->order_data['cost'])?>
            </strong>
        </td>
    </tr>
    </tfoot>
</table>

<pre>
<?php
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
    echo "\n\n";
    echo 'Наши контакты:'."\n";
    foreach (Yii::$app->params['contacts'] as $contact => $value) {
        echo StringsHelper::mb_sprintf('%10s : %s', $contact, implode(', ', (array)$value)) . "\n";
    }
?>
</pre>