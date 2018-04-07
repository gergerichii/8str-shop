<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 06.04.2018
 * Time: 16:02
 */

use yii\web\View;

/** @var View $this */
$this->title = 'Доставка и оплата';
?>

<div class="row">
    <div class="col-md-12">
        <div class="row slider-position">
            <div class="md-margin"></div><!-- space -->
            
            <?=$this->render('@app/views/pieces/sidebar');?>

            <div class="col-md-9 col-sm-8 col-xs-12 main-content ">
                <header class="content-title">
                    <h1 class="title"><?=$this->title?></h1>
                    <div class="md-margin"></div><!-- space -->
                </header>
                <h3>Оплата</h3>
                <p><span style="color:#000000;">Выберите наиболее удобный для вас способ оплаты.</span></p>
                <p><strong style="color: rgb(0, 0, 0); font-size: 10pt;">1. Наличный расчет</strong></p>
                <p><span style="color: rgb(0, 0, 0); font-family: helvetica, tahoma, verdana, Helvetica, sans-serif; font-size: 13px;">Оплата наличными производится курьеру при доставке, либо в пункте самовывоза. Вместе с товаром Вам передаются товарный и кассовый чеки.&nbsp;</span></p>
                <p><span style="color:#000000;"><strong>2. Безналичный расчет.</strong> Вам выставляется расчетный счет на оплату заказа, который можно оплатить в любом отделении банка России.</span></p>
                <p><span style="color:#000000;">Оплата по безналичному расчёту через банк осуществляется по следующим реквизитам:</span></p>
                <p><span style="color:#000000;">р/с 40802810400000000112</span></p>
                <p><span style="color:#000000;">АКБ &quot;Новый кредитный союз&quot; г Москва</span></p>
                <p><span style="color:#000000;">К/С 30101810600000000456</span></p>
                <p><span style="color:#000000;">БИК 044585456</span></p>
                <p><span style="color:#000000;">ИНН 770105288943</span></p>
                <p>*при оплате по безналичному расчету на некоторые товары брендов: Hikvision, HiWatch, Ezviz действует наценка 8%</p>
                <p><span style="color:#000000;"><strong>3. Денежные переводы с карты на карту Сбербанка.</strong></span></p>
                <p><span style="color:#000000;">Перевод денег с карты на карту Сбербанка производится почти мгновенно, что экономит время и деньги.</span></p>
                <p><span style="color:#000000;">Денежные переводы с карты на карту Сбербанка доступны к осуществлению следующими способами:<span style="font-size: 10pt;">&middot;&nbsp;</span></span></p>
                <ul>
                    <li>
                        <span style="color:#000000;"><span style="font-size: 10pt;">Перевод через банкоматы Сбербанка России;</span><span style="font-size: 10pt;">&nbsp; &nbsp; &nbsp; &nbsp; </span></span></li>
                    <li>
                        <span style="color:#000000;"><span style="font-size: 10pt;">Перевод через информационно-платежные терминалы самообслуживания Сбербанка.</span><span style="font-size: 10pt;">&nbsp; &nbsp; &nbsp;</span></span></li>
                    <li>
                        <span style="color:#000000;"><span style="font-size: 10pt;">Перевод через систему &quot;Сбербанк ОнЛ@йн&quot;.</span><span style="font-size: 10pt;">&nbsp;</span></span></li>
                    <li>
                        <span style="color:#000000;"><span style="font-size: 10pt;">Перевод через систему &quot;Мобильный банк&quot;</span></span></li>
                </ul>
                <p>&nbsp;</p>
                <p><span style="color:#000000;">Оплата на карту осуществляется по следующим реквизитам:</span></p>
                <p><span style="color:#000000;">№ карты Сбербанка: 5469 3800 3896 8672</span></p>
                <p><span style="color:#000000;">л/с 4081 7810 8382 91 92 76 90</span></p>
                <p><span style="color:#000000;">бик 044525225</span></p>
                <p><span style="color:#000000;">к/с 3010181040 00 00 00 02 25</span></p>
                <p><span style="color:#000000;">Имя получателя: Тимофей Владимирович Богомолов</span></p>
                <h3>Доставка</h3>
                <p><span style="color:#000000;">Доставка заказа клиентам производится несколькими способами, выбор одного из них осуществляется заказчиком при оформлении заказа.</span></p>
                <p><span style="color:#000000;">Заказы отправляются в течение 1-2 рабочих дней после получения заявки или оплаты.</span></p>
                <p><span style="color:#000000;">1.&nbsp;&nbsp;&nbsp;<strong>Доставка курьером</strong></span></p>
                <p style="margin-left:18.0pt;"><span style="color:#000000;">Стоимость доставка по Москве в пределах МКАД&nbsp; и вблизи станций метро-&nbsp; 350 руб.</span></p>
                <p style="margin-left:18.0pt;"><span style="color:#000000;">- до 10 км от МКАД - 600 руб.&nbsp;</span></p>
                <p style="margin-left:18.0pt;"><span style="color:#000000;">-свыше 10 км от МКАД - 1200 руб.</span></p>
                <p style="margin-left:18.0pt;"><span style="color:#000000;">Если Вы откажетесь от покупки в момент получения товара, Вам необходимо будет оплатить стоимость доставки товара (Гражданский Кодекс РФ, статья 497, пункт 3).</span></p>
                <p><span style="color:#000000;">2. &nbsp; <strong>Доставка почтой</strong></span></p>
                <p><span style="color:#000000;">Доставка по территории России осуществляется следующими компаниями: EMS Почта России, DHL, TNT, Деловые Линии, ПЭК, Почта России, Транспортная компания Кит. Стоимость доставки до терминала транспортной компании - 350 руб.</span></p>
                <p><span style="color:#000000;">3.&nbsp;</span><strong style="color: rgb(0, 0, 0); font-size: 10pt;">&nbsp;Самовывоз</strong></p>
                <p><span style="color:#000000;">Мы находимся по адресу: г. Москва, Универмаг Московский, Комсомольская пл., д.6 стр1 офис 526</span></p>
                <p><span style="color:#000000;">Заказанный вами товар можно оплатить наличными и получить лично. На месте вы можете посмотреть товар.</span></p>
                <h3>Возврат товара</h3>
                <p><span style="color:#000000;">В соответствии со статьей 26.1 Закона РФ &laquo;О защите прав потребителей&raquo;, при покупке товара дистанционно (через интернет с получением по почте) Вы можете в течение семи дней после получения вернуть товар, если сохранены его товарный вид, потребительские свойства, а также документ, подтверждающий факт и условия покупки указанного товара. Для возврата свяжитесь с нами через раздел &laquo;Контакты&raquo;. Стоимость отправки покупателю не возвращается. Отправка товара продавцу производится за счет покупателя.</span></p>
            </div><!-- End .col-md-9 -->
        </div><!-- End .row -->
    </div><!-- End .col-md-12 -->
</div><!-- End .row -->
