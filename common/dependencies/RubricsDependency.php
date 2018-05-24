<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 24.05.2018
 * Time: 13:57
 */

namespace common\dependencies;
use yii\caching\ChainedDependency;
use yii\caching\DbDependency;
use yii\caching\TagDependency;

class RubricsDependency extends ChainedDependency {
    private $_changed = null;
    
    public function init() {
        parent::init();
        $this->dependencies = [
            new DbDependency([
                'sql' => \Yii::$app->db->createCommand(
                    "SELECT `UPDATE_TIME` FROM `INFORMATION_SCHEMA`.`PARTITIONS` WHERE `TABLE_SCHEMA` = 'tima_shop' AND `TABLE_NAME` = 'product_rubric'"
                )->sql
            ]),
            new TagDependency(['tags' => [
                CacheTags::CATALOG, CacheTags::RUBRICS,
            ]]),
        ];
    }
    
    public function isChanged($cache) {
        if (is_null($this->_changed)) {
            $this->_changed = parent::isChanged($cache);
        }
        return $this->_changed;
    }
    
    public function clearChanged() {
        $this->_changed = null;
    }
}