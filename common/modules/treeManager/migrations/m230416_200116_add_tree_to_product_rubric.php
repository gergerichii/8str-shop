<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015 - 2017
 * @package yii2-tree-manager
 * @version 1.0.9
 * @see http://demos.krajee.com/tree-manager
 */

use yii\db\Migration;

/**
 * Migration for creating the database structure for the kartik-v/yii2-tree-manager module.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class m230416_200116_add_tree_to_product_rubric extends Migration
{
    const TABLE_NAME = '{{%product_rubric}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->addColumn(self::TABLE_NAME, 'root', $this->integer());
        $this->addColumn(self::TABLE_NAME, 'icon', $this->string(255));
        $this->addColumn(self::TABLE_NAME, 'icon_type', $this->smallInteger(1)->notNull()->defaultValue(1));
        $this->addColumn(self::TABLE_NAME, 'active', $this->boolean()->notNull()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME, 'selected', $this->boolean()->notNull()->defaultValue(false));
        $this->addColumn(self::TABLE_NAME, 'disabled', $this->boolean()->notNull()->defaultValue(false));
        $this->addColumn(self::TABLE_NAME, 'readonly', $this->boolean()->notNull()->defaultValue(false));
        $this->addColumn(self::TABLE_NAME, 'visible', $this->boolean()->notNull()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME, 'collapsed', $this->boolean()->notNull()->defaultValue(false));
        $this->addColumn(self::TABLE_NAME, 'movable_u', $this->boolean()->notNull()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME, 'movable_d', $this->boolean()->notNull()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME, 'movable_l', $this->boolean()->notNull()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME, 'movable_r', $this->boolean()->notNull()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME, 'removable', $this->boolean()->notNull()->defaultValue(true));
        $this->addColumn(self::TABLE_NAME, 'removable_all', $this->boolean()->notNull()->defaultValue(false));

        $this->createIndex('tree_NK1', self::TABLE_NAME, 'root');
        $this->createIndex('tree_NK2', self::TABLE_NAME, 'left_key');
        $this->createIndex('tree_NK3', self::TABLE_NAME, 'right_key');
        $this->createIndex('tree_NK4', self::TABLE_NAME, 'level');
        $this->createIndex('tree_NK5', self::TABLE_NAME, 'active');
    }


    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
