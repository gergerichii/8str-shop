<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_type`.
 *
 * Таблица типов (видов) продуктов. В ней хранятся названия типов для вывода в списке
 * и шаблоны для структуры дополнительных атрибутов продукта в виде JSON
 * Структуру JSON ищи в common\modules\catalog\models\ProductType
 * Пример дополнительных аттрибутов:
 * Зарядное устройство (поля: входное напряжение, ампераж и т.д.)
 * Камера (поля: разрешение матрицы, вариофакал и пр.)
 *
 * Зависящие таблицы: product
 **/
class m180105_143723_create_product_type_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up() {
        $tableComment = <<<EOT
'Таблица типов (видов) продуктов. В ней хранятся названия типов для вывода в списке
и шаблоны для структуры дополнительных атрибутов продукта в виде JSON 
Структуру JSON ищи в common\\\\models\\\\entities\\\\ProductType
Пример дополнительных аттрибутов: 
        Зарядное устройство (поля: входное напряжение, ампераж и т.д.)
        Камера (поля: разрешение матрицы, вариофакал и пр.)
        
Зависящие таблицы: product'
EOT;

        $this->createTable('{{%product_type}}', [
            '[[id]]' => $this->primaryKey(11),
            '[[name]]' => $this->string(150)->unique()->notNull(),
            '[[desc]]' => $this->text(),
            /** JSON шаблон с дополнительными атрибутами для продукта. См. product.attributes */
            '[[template]]' => 'JSON NOT NULL CHECK(JSON_VALID([[template]]))',
        ], 'ENGINE=InnoDB, COMMENT=' . $tableComment . ", COLLATE 'utf8_general_ci'");

        $this->insert('{{%product_type}}', [
            '[[id]]' => 1,
            '[[name]]' => 'Продукт без типа',
            '[[desc]]' => 'Устанавливается по умолчанию для новых товаров',
            '[[template]]' => '{"bestseller": "boolean"}',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%product_type}}');
    }
}
