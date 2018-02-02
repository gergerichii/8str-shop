<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product`.
 *
 * Основная таблица товаров. Расширенные атрибуты, цены, файлы и служебные данные 1С находятся в формате JSON
 * Для уточнения структуры данных JSON смотри common\modules\catalog\models\Product и соответствующие хелперы.
 *
 * Зависит от таблиц: product_type, product_brand и user
 * Зависящие таблицы: tag, related_products, product_rubric, product_price
 */
class m180105_145904_create_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableComment =<<<EOT
'Основная таблица товаров. Расширенные атрибуты, цены, файлы и служебные данные 1С находятся в формате JSON
Для уточнения структуры данных JSON смотри common\\\\models\\\\entities\\\\Product и соответствующие хелперы.

[[title]] => Название для вывода пользователю,
[[status]] => Статус продукта. Влияет на видимость в админке и на фронте. Номера статусов смотри в классе модели или в хелперах,
[[count]] => При значении = 0, к товару будет выводиться пометка: только под заказ,
[[attributes]] => Атрибуты товаров в формате JSON вместо EAV,
[[files]] => Ссылки в формате JSOM (с алиасами пути) на файлы (В том числе и картинки), прикрепленные к товару, и доп инфа к ним.,
[[1c_data]] => Служебные данные для контактирования с 1С,
[[delivery]]_time => Срок поставки товара на склад. В днях.,
[[creator_id]] => ID сотрудника который добавил товар. Ссылка на таблицу User,
[[modifier_id]] => ID сотрудника который последний изменял товар. Ссылка на таблицу User,
[[product_type_id]] => Тип продукта. Влияет на дополнительные поля продукта. Ссылается product_type,
[[brand_id]] => Брэнд (изготовитель) продукта. Ссылается product_brand,

Зависит от таблиц: product_type, product_brand и user
Зависящие таблицы: tag, related_products, product_rubric, product_price'
EOT;

        $this->createTable('{{%product}}', [
            '[[id]]' => $this->primaryKey(11),
            '[[name]]' => $this->string(150)->notNull()->unique(),
            /** Название для вывода пользователю */
            '[[title]]' => $this->string(255)->null(),
            '[[desc]]' => $this->text(),
            /** Статус продукта. Влияет на видимость в админке и на фронте. */
            '[[status]]' => $this->integer(2)->notNull()
                ->defaultValue(1),
            /** При значении = 0, к товару будет выводиться пометка: только под заказ */
            '[[count]]' => $this->integer(11)->notNull()->defaultValue(1),
            /** Показывать на главной странице */
            '[[show_on_home]]' => $this->boolean()->notNull()->defaultValue(FALSE),
            /** Закрепить вверху списков */
            '[[on_list_top]]' => $this->boolean()->notNull()->defaultValue(FALSE),
            /** Выгружать на маркет */
            '[[market_upload]]' => $this->boolean()->notNull()->defaultValue(FALSE),
            /** Это новый товар */
            '[[is_new]]' => $this->boolean()->notNull()->defaultValue(FALSE),
            /** Атрибуты товаров вместо EAV */
            '[[ext_attributes]]' => 'JSON NOT NULL CHECK(JSON_VALID([[ext_attributes]]))',
            /** Ссылки в формате JSOM (с алиасами пути) на файлы (В том числе и картинки), прикрепленные к товару, и доп инфа к ним. */
            '[[files]]' => 'JSON NOT NULL CHECK(JSON_VALID([[files]]))',
            /** Служебные данные для контактирования с 1С */
            '[[1c_data]]' => 'JSON NOT NULL CHECK(JSON_VALID([[1c_data]]))',
            /** Срок поставки товара на склад. В днях. */
            '[[delivery_time]]' => $this->integer()->notNull()->defaultValue(1),
            '[[created_at]]' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
            '[[modified_at]]' => $this->timestamp()->null()
                ->defaultExpression('NULL ON UPDATE CURRENT_TIMESTAMP'),
            /** ID сотрудника который добавил товар. Ссылка на таблицу User */
            '[[creator_id]]' => $this->integer(11)->notNull(),
            /** ID сотрудника который последний изменял товар. Ссылка на таблицу User */
            '[[modifier_id]]' => $this->integer(11)->notNull(),
            /** Тип продукта. Влияет на дополнительные поля продукта. Ссылается product_type */
            '[[product_type_id]]' => $this->integer(11)->notNull()
                ->defaultValue(1),
            /** Брэнд (изготовитель) продукта. Ссылается product_brand */
            '[[brand_id]]' => $this->integer(11)->null(),
        ], 'ENGINE=InnoDB, COMMENT=' . $tableComment . ", COLLATE 'utf8_general_ci'");

        $this->createIndex('product_title', '{{%product}}', ['[[title]]']);
        $this->createIndex('product_status', '{{%product}}', ['[[status]]']);
        $this->createIndex('product_product_type_id', '{{%product}}', ['[[product_type_id]]']);
        $this->createIndex('product_brand_id', '{{%product}}', ['[[brand_id]]']);

        $this->addForeignKey(
            'fk_product_product_type_id',
            '{{%product}}',
            '[[product_type_id]]',
            '{{%product_type}}',
            '[[id]]',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_product_brand_id',
            '{{%product}}',
            '[[brand_id]]',
            '{{%product_brand}}',
            '[[id]]',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_product_creator_id',
            '{{%product}}',
            '[[creator_id]]',
            '{{%user}}',
            '[[id]]',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_product_modifier_id',
            '{{%product}}',
            '[[modifier_id]]',
            '{{%user}}',
            '[[id]]',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_product_brand_id', '{{%product}}');
        $this->dropForeignKey('fk_product_product_type_id', '{{%product}}');
        $this->dropForeignKey('fk_product_creator_id', '{{%product}}');
        $this->dropForeignKey('fk_product_modifier_id', '{{%product}}');
        $this->dropTable('{{product}}');
    }
}
