<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 03.01.2018
 * Time: 9:58
 */

namespace common\base\models\nestedSets;

use common\behaviors\SlugBehavior;
use \common\base\models\BaseActiveRecord;
use yii\base\NotSupportedException;
use yii\db\Exception;
use yii\db\Expression;

/** @noinspection UndetectableTableInspection */

/**
 * Class MyNestedSetsActiveRecord
 * @package common\components
 *
 * @property string $slug
 */
class NSActiveRecord extends BaseActiveRecord {

    const OPERATION_MAKE_ROOT = 'makeRoot';
    const OPERATION_PREPEND_TO = 'prependTo';
    const OPERATION_APPEND_TO = 'appendTo';
    const OPERATION_INSERT_BEFORE = 'insertBefore';
    const OPERATION_INSERT_AFTER = 'insertAfter';
    const OPERATION_DELETE_WITH_CHILDREN = 'deleteWithChildren';

    /**
     * @var string|false
     */
    public $treeAttribute = 'tree';
    /**
     * @var string
     */
    public $leftAttribute = 'left_key';
    /**
     * @var string
     */
    public $rightAttribute = 'right_key';
    /**
     * @var string
     */
    public $depthAttribute = 'level';
    /**
     * @var string
     */
    public $pathAttribute = 'material_path';
    /**
     * @var string
     */
    protected $_operation;
    /**
     * @var NSActiveRecord $_node
     */
    protected $_node;

    /**
     * @inheritdoc
     */
    public function behaviors ()
    {
        return [
            'slugBehavior' => [
                'class' => SlugBehavior::className(),
                'slugField' => 'name',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
//            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
//            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
//            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
//            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
//            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
//            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    /**
     * @inheritdoc
     * @return NSActiveQuery the active query used by this AR class.
     */
    public static function find() {
        return new NSActiveQuery(get_called_class());
    }

    /**
     * Creates the root node if the active record is new or moves it
     * as the root node.
     * @param boolean $runValidation
     * @param array $attributes
     * @return boolean
     */
    public function makeRoot($runValidation = true, $attributes = null) {
        $this->_operation = self::OPERATION_MAKE_ROOT;

        return $this->save($runValidation, $attributes);
    }

    /**
     * Creates a node as the first child of the target node if the active
     * record is new or moves it as the first child of the target node.
     * @param NSActiveRecord $node
     * @param boolean $runValidation
     * @param array $attributes
     * @return boolean
     */
    public function prependTo($node, $runValidation = true, $attributes = null) {
        $this->_operation = self::OPERATION_PREPEND_TO;
        $this->_node = $node;

        return $this->save($runValidation, $attributes);
    }

    /**
     * Creates a node as the last child of the target node if the active
     * record is new or moves it as the last child of the target node.
     * @param NSActiveRecord $node
     * @param boolean $runValidation
     * @param array $attributes
     * @return boolean
     */
    public function appendTo($node, $runValidation = true, $attributes = null) {
        $this->_operation = self::OPERATION_APPEND_TO;
        $this->_node = $node;

        return $this->save($runValidation, $attributes);
    }

    /**
     * Creates a node as the previous sibling of the target node if the active
     * record is new or moves it as the previous sibling of the target node.
     * @param NSActiveRecord $node
     * @param boolean $runValidation
     * @param array $attributes
     * @return boolean
     */
    public function insertBefore($node, $runValidation = true, $attributes = null)
    {
        $this->_operation = self::OPERATION_INSERT_BEFORE;
        $this->_node = $node;

        return $this->save($runValidation, $attributes);
    }

    /**
     * Creates a node as the next sibling of the target node if the active
     * record is new or moves it as the next sibling of the target node.
     * @param NSActiveRecord $node
     * @param boolean $runValidation
     * @param array $attributes
     * @return boolean
     */
    public function insertAfter($node, $runValidation = true, $attributes = null)
    {
        $this->_operation = self::OPERATION_INSERT_AFTER;
        $this->_node = $node;

        return $this->save($runValidation, $attributes);
    }

    /**
     * Deletes a node and its children.
     * @return integer|false the number of rows deleted or false if
     * the deletion is unsuccessful for some reason.
     * @throws \Exception
     */
    public function deleteWithChildren()
    {
        $this->_operation = self::OPERATION_DELETE_WITH_CHILDREN;

        if (!$this->isTransactional(NSActiveRecord::OP_DELETE)) {
            return $this->deleteWithChildrenInternal();
        }

        $transaction = $this->getDb()->beginTransaction();

        try {
            $result = $this->deleteWithChildrenInternal();

            if ($result === false) {
                $transaction->rollBack();
            } else {
                $transaction->commit();
            }

            return $result;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @throws Exception
     * @throws NotSupportedException
     *
     * @return integer|false the number of rows deleted or false if
     * the deletion is unsuccessful for some reason.
     */
    protected function deleteWithChildrenInternal() {
        if (!$this->beforeDelete()) {
            return false;
        }

        $condition = [
            'and',
            ['>=', $this->leftAttribute, $this->getAttribute($this->leftAttribute)],
            ['<=', $this->rightAttribute, $this->getAttribute($this->rightAttribute)]
        ];

        $this->applyTreeAttributeCondition($condition);
        $result = $this->deleteAll($condition);
        $this->setOldAttributes(null);
        $this->afterDelete();

        return $result;
    }

    /**
     * Gets the parents of the node.
     * @param integer|null $depth the depth
     * @param bool $orderDesc
     * @return \yii\db\ActiveQuery
     *
     */
    public function parents($depth = null, $orderDesc = false)
    {
        $condition = [
            'and',
            ['<', $this->leftAttribute, $this->getAttribute($this->leftAttribute)],
            ['>', $this->rightAttribute, $this->getAttribute($this->rightAttribute)],
        ];

        if ($depth !== null) {
            $condition[] = ['>=', $this->depthAttribute, $this->getAttribute($this->depthAttribute) - $depth];
        }

        $this->applyTreeAttributeCondition($condition);

        return $this->find()->andWhere($condition)
            ->addOrderBy([$this->leftAttribute => ($orderDesc) ? SORT_DESC : SORT_ASC]);
    }

    /**
     * Gets the children of the node.
     * @param integer|null $depth the depth
     * @return \yii\db\ActiveQuery
     */
    public function children($depth = null)
    {
        $condition = [
            'and',
            ['>', $this->leftAttribute, $this->getAttribute($this->leftAttribute)],
            ['<', $this->rightAttribute, $this->getAttribute($this->rightAttribute)],
        ];

        if ($depth !== null) {
            $condition[] = ['<=', $this->depthAttribute, $this->getAttribute($this->depthAttribute) + $depth];
        }

        $this->applyTreeAttributeCondition($condition);

        return $this->find()->andWhere($condition)->addOrderBy([$this->leftAttribute => SORT_ASC]);
    }

    /**
     * Gets the leaves of the node.
     * @return \yii\db\ActiveQuery
     */
    public function leaves()
    {
        $condition = [
            'and',
            ['>', $this->leftAttribute, $this->getAttribute($this->leftAttribute)],
            ['<', $this->rightAttribute, $this->getAttribute($this->rightAttribute)],
            [$this->rightAttribute => new Expression($this->getDb()->quoteColumnName($this->leftAttribute) . '+ 1')],
        ];

        $this->applyTreeAttributeCondition($condition);

        return $this->find()->andWhere($condition)->addOrderBy([$this->leftAttribute => SORT_ASC]);
    }

    /**
     * Gets the previous sibling of the node.
     * @return \yii\db\ActiveQuery
     */
    public function prev()
    {
        $condition = [$this->rightAttribute => $this->getAttribute($this->leftAttribute) - 1];
        $this->applyTreeAttributeCondition($condition);

        return $this->find()->andWhere($condition);
    }

    /**
     * Gets the next sibling of the node.
     * @return \yii\db\ActiveQuery
     */
    public function next()
    {
        $condition = [$this->leftAttribute => $this->getAttribute($this->rightAttribute) + 1];
        $this->applyTreeAttributeCondition($condition);

        return $this->find()->andWhere($condition);
    }

    /**
     * Determines whether the node is root.
     * @return boolean whether the node is root
     */
    public function isRoot()
    {
        return $this->getAttribute($this->leftAttribute) == 1;
    }

    /**
     * Determines whether the node is child of the parent node.
     * @param NSActiveRecord $node the parent node
     * @return boolean whether the node is child of the parent node
     */
    public function isChildOf($node)
    {
        $result = $this->getAttribute($this->leftAttribute) > $node->getAttribute($this->leftAttribute)
            && $this->getAttribute($this->rightAttribute) < $node->getAttribute($this->rightAttribute);

        if ($result && $this->treeAttribute !== false) {
            $result = $this->getAttribute($this->treeAttribute) === $node->getAttribute($this->treeAttribute);
        }

        return $result;
    }

    /**
     * Determines whether the node is leaf.
     * @return boolean whether the node is leaf
     */
    public function isLeaf()
    {
        return $this->getAttribute($this->rightAttribute) - $this->getAttribute($this->leftAttribute) === 1;
    }

    protected function setCurrentPath() {
        $path = [];
        switch ($this->_operation) {
            case self::OPERATION_INSERT_BEFORE:
            case self::OPERATION_INSERT_AFTER:
            case self::OPERATION_APPEND_TO:
            /** @noinspection PhpMissingBreakStatementInspection */
            case self::OPERATION_PREPEND_TO:
                /** @var NSActiveRecord[] $parentParents */
                $parentParents = $this->_node->parents()
                    ->orderBy($this->depthAttribute)->all();

                foreach ($parentParents as $parent) {
                    $path[] = $parent->slug;
                }

                if (in_array($this->_operation, [self::OPERATION_PREPEND_TO, self::OPERATION_APPEND_TO])){
                    $path[] = $this->_node->slug;
                }
            case self::OPERATION_MAKE_ROOT:
                $path[] = $this->slug;
                $path = implode('/', $path);
                $this->setAttribute($this->pathAttribute, $path);
        }
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws Exception
     * @throws NotSupportedException
     */
    public function beforeSave($insert) {
        $this->setCurrentPath();
        $insert ? $this->beforeInsert() : $this->beforeUpdate();
        return parent::beforeSave($insert);
    }

    /**
     * @throws NotSupportedException
     * @throws Exception
     */
    public function beforeInsert() {
        if ($this->_node !== null && !$this->_node->getIsNewRecord()) {
            $this->_node->refresh();
        }

        switch ($this->_operation) {
            case self::OPERATION_MAKE_ROOT:
                $this->beforeInsertRootNode();
                break;
            case self::OPERATION_PREPEND_TO:
                $this->beforeInsertNode($this->_node->getAttribute($this->leftAttribute) + 1, 1);
                break;
            case self::OPERATION_APPEND_TO:
                $this->beforeInsertNode($this->_node->getAttribute($this->rightAttribute), 1);
                break;
            case self::OPERATION_INSERT_BEFORE:
                $this->beforeInsertNode($this->_node->getAttribute($this->leftAttribute), 0);
                break;
            case self::OPERATION_INSERT_AFTER:
                $this->beforeInsertNode($this->_node->getAttribute($this->rightAttribute) + 1, 0);
                break;
            default:
                throw new NotSupportedException('Method "'. get_class($this) . '::insert" is not supported for inserting new nodes.');
        }
    }

    /**
     * @throws Exception
     */
    protected function beforeInsertRootNode()
    {
        if ($this->treeAttribute === false && $this->find()->roots()->exists()) {
            throw new Exception('Can not create more than one root when "treeAttribute" is false.');
        }

        $this->setAttribute($this->leftAttribute, 1);
        $this->setAttribute($this->rightAttribute, 2);
        $this->setAttribute($this->depthAttribute, 0);
        
        $tree = static::find()->select(['MAX([[tree]]) AS [[tree]]'])->all()[0]->getAttribute($this->treeAttribute) + 1;
        $this->setAttribute($this->treeAttribute, $tree);
    }

    /**
     * @param integer $value
     * @param integer $depth
     * @throws Exception
     */
    protected function beforeInsertNode($value, $depth)
    {
        if ($this->_node->getIsNewRecord()) {
            throw new Exception('Can not create a node when the target node is new record.');
        }

        if ($depth === 0 && $this->_node->isRoot()) {
            throw new Exception('Can not create a node when the target node is root.');
        }

        $this->setAttribute($this->leftAttribute, $value);
        $this->setAttribute($this->rightAttribute, $value + 1);
        $this->setAttribute($this->depthAttribute, $this->_node->getAttribute($this->depthAttribute) + $depth);

        if ($this->treeAttribute !== false) {
            $this->setAttribute($this->treeAttribute, $this->_node->getAttribute($this->treeAttribute));
        }

        $this->shiftLeftRightAttribute($value, 2);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @throws Exception
     */
    public function afterSave($insert, $changedAttributes) {
        $insert ? $this->afterInsert() : $this->afterUpdate();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @throws Exception
     */
    public function afterInsert()
    {
        if ($this->_operation === self::OPERATION_MAKE_ROOT && $this->treeAttribute !== false) {
            $this->setAttribute($this->treeAttribute, $this->getPrimaryKey());
            $primaryKey = $this->primaryKey();

            if (!isset($primaryKey[0])) {
                throw new Exception('"' . get_class($this) . '" must have a primary key.');
            }

            $this->updateAll(
                [$this->treeAttribute => $this->getAttribute($this->treeAttribute)],
                [$primaryKey[0] => $this->getAttribute($this->treeAttribute)]
            );
        }

        $this->_operation = null;
        $this->_node = null;
    }

    /**
     * @throws Exception
     */
    public function beforeUpdate()
    {
        if ($this->_node !== null && !$this->_node->getIsNewRecord()) {
            $this->_node->refresh();
        }

        switch ($this->_operation) {
            case self::OPERATION_MAKE_ROOT:
                if ($this->treeAttribute === false) {
                    throw new Exception('Can not move a node as the root when "treeAttribute" is false.');
                }

                if ($this->isRoot()) {
                    throw new Exception('Can not move the root node as the root.');
                }

                break;
            case self::OPERATION_INSERT_BEFORE:
            case self::OPERATION_INSERT_AFTER:
                if ($this->_node->isRoot()) {
                    throw new Exception('Can not move a node when the target node is root.');
                }
                break;
            case self::OPERATION_PREPEND_TO:
            case self::OPERATION_APPEND_TO:
                if ($this->_node->getIsNewRecord()) {
                    throw new Exception('Can not move a node when the target node is new record.');
                }

                if ($this->equals($this->_node)) {
                    throw new Exception('Can not move a node when the target node is same.');
                }

                if ($this->_node->isChildOf($this)) {
                    throw new Exception('Can not move a node when the target node is child.');
                }
        }
    }

    /**
     * @return void
     */
    public function afterUpdate()
    {
        switch ($this->_operation) {
            case self::OPERATION_MAKE_ROOT:
                $this->moveNodeAsRoot();
                break;
            case self::OPERATION_PREPEND_TO:
                $this->moveNode($this->_node->getAttribute($this->leftAttribute) + 1, 1);
                break;
            case self::OPERATION_APPEND_TO:
                $this->moveNode($this->_node->getAttribute($this->rightAttribute), 1);
                break;
            case self::OPERATION_INSERT_BEFORE:
                $this->moveNode($this->_node->getAttribute($this->leftAttribute), 0);
                break;
            case self::OPERATION_INSERT_AFTER:
                $this->moveNode($this->_node->getAttribute($this->rightAttribute) + 1, 0);
                break;
            default:
                return;
        }

        $this->_operation = null;
        $this->_node = null;
    }

    /**
     * @return void
     */
    protected function moveNodeAsRoot()
    {
        $db = $this->getDb();
        $leftValue = $this->getAttribute($this->leftAttribute);
        $rightValue = $this->getAttribute($this->rightAttribute);
        $depthValue = $this->getAttribute($this->depthAttribute);
        $treeValue = $this->getAttribute($this->treeAttribute);
        $leftAttribute = $db->quoteColumnName($this->leftAttribute);
        $rightAttribute = $db->quoteColumnName($this->rightAttribute);
        $depthAttribute = $db->quoteColumnName($this->depthAttribute);

        $this->updateAll(
            [
                $this->leftAttribute => new Expression($leftAttribute . sprintf('%+d', 1 - $leftValue)),
                $this->rightAttribute => new Expression($rightAttribute . sprintf('%+d', 1 - $leftValue)),
                $this->depthAttribute => new Expression($depthAttribute  . sprintf('%+d', -$depthValue)),
                $this->treeAttribute => $this->getPrimaryKey(),
            ],
            [
                'and',
                ['>=', $this->leftAttribute, $leftValue],
                ['<=', $this->rightAttribute, $rightValue],
                [$this->treeAttribute => $treeValue]
            ]
        );

        $this->shiftLeftRightAttribute($rightValue + 1, $leftValue - $rightValue - 1);
    }

    /**
     * @param integer $value
     * @param integer $depth
     */
    protected function moveNode($value, $depth)
    {
        $db = $this->getDb();
        $leftValue = $this->getAttribute($this->leftAttribute);
        $rightValue = $this->getAttribute($this->rightAttribute);
        $depthValue = $this->getAttribute($this->depthAttribute);
        $depthAttribute = $db->quoteColumnName($this->depthAttribute);
        $depth = $this->_node->getAttribute($this->depthAttribute) - $depthValue + $depth;

        if ($this->treeAttribute === false
            || $this->getAttribute($this->treeAttribute) === $this->_node->getAttribute($this->treeAttribute)) {
            $delta = $rightValue - $leftValue + 1;
            $this->shiftLeftRightAttribute($value, $delta);

            if ($leftValue >= $value) {
                $leftValue += $delta;
                $rightValue += $delta;
            }

            $condition = ['and', ['>=', $this->leftAttribute, $leftValue], ['<=', $this->rightAttribute, $rightValue]];
            $this->applyTreeAttributeCondition($condition);

            $this->updateAll(
                [$this->depthAttribute => new Expression($depthAttribute . sprintf('%+d', $depth))],
                $condition
            );

            foreach ([$this->leftAttribute, $this->rightAttribute] as $attribute) {
                $condition = ['and', ['>=', $attribute, $leftValue], ['<=', $attribute, $rightValue]];
                $this->applyTreeAttributeCondition($condition);

                $this->updateAll(
                    [$attribute => new Expression($db->quoteColumnName($attribute) . sprintf('%+d', $value - $leftValue))],
                    $condition
                );
            }

            $this->shiftLeftRightAttribute($rightValue + 1, -$delta);
        } else {
            $leftAttribute = $db->quoteColumnName($this->leftAttribute);
            $rightAttribute = $db->quoteColumnName($this->rightAttribute);
            $nodeRootValue = $this->_node->getAttribute($this->treeAttribute);

            foreach ([$this->leftAttribute, $this->rightAttribute] as $attribute) {
                $this->updateAll(
                    [$attribute => new Expression($db->quoteColumnName($attribute) . sprintf('%+d', $rightValue - $leftValue + 1))],
                    ['and', ['>=', $attribute, $value], [$this->treeAttribute => $nodeRootValue]]
                );
            }

            $delta = $value - $leftValue;

            $this->updateAll(
                [
                    $this->leftAttribute => new Expression($leftAttribute . sprintf('%+d', $delta)),
                    $this->rightAttribute => new Expression($rightAttribute . sprintf('%+d', $delta)),
                    $this->depthAttribute => new Expression($depthAttribute . sprintf('%+d', $depth)),
                    $this->treeAttribute => $nodeRootValue,
                ],
                [
                    'and',
                    ['>=', $this->leftAttribute, $leftValue],
                    ['<=', $this->rightAttribute, $rightValue],
                    [$this->treeAttribute => $this->getAttribute($this->treeAttribute)],
                ]
            );

            $this->shiftLeftRightAttribute($rightValue + 1, $leftValue - $rightValue - 1);
        }
    }

    /**
     * @throws Exception
     * @throws NotSupportedException
     *
     *
     */
    public function beforeDelete() {
        if ($this->getIsNewRecord()) {
            throw new Exception('Can not delete a node when it is new record.');
        }

        if ($this->isRoot() && $this->_operation !== self::OPERATION_DELETE_WITH_CHILDREN) {
            throw new NotSupportedException('Method "'. get_class($this) . '::delete" is not supported for deleting root nodes.');
        }

        $this->refresh();
        return parent::beforeDelete();
    }

    /**
     * @return void
     */
    public function afterDelete()
    {
        $leftValue = $this->getAttribute($this->leftAttribute);
        $rightValue = $this->getAttribute($this->rightAttribute);

        if ($this->isLeaf() || $this->_operation === self::OPERATION_DELETE_WITH_CHILDREN) {
            $this->shiftLeftRightAttribute($rightValue + 1, $leftValue - $rightValue - 1);
        } else {
            $condition = [
                'and',
                ['>=', $this->leftAttribute, $this->getAttribute($this->leftAttribute)],
                ['<=', $this->rightAttribute, $this->getAttribute($this->rightAttribute)]
            ];

            $this->applyTreeAttributeCondition($condition);
            $db = $this->getDb();

            $this->updateAll(
                [
                    $this->leftAttribute => new Expression($db->quoteColumnName($this->leftAttribute) . sprintf('%+d', -1)),
                    $this->rightAttribute => new Expression($db->quoteColumnName($this->rightAttribute) . sprintf('%+d', -1)),
                    $this->depthAttribute => new Expression($db->quoteColumnName($this->depthAttribute) . sprintf('%+d', -1)),
                ],
                $condition
            );

            $this->shiftLeftRightAttribute($rightValue + 1, -2);
        }

        $this->_operation = null;
        $this->_node = null;

        parent::afterDelete();
    }

    /**
     * @param integer $value
     * @param integer $delta
     */
    protected function shiftLeftRightAttribute($value, $delta)
    {
        $db = $this->getDb();

        foreach ([$this->leftAttribute, $this->rightAttribute] as $attribute) {
            $condition = ['>=', $attribute, $value];
            $this->applyTreeAttributeCondition($condition);

            $this->updateAll(
                [$attribute => new Expression($db->quoteColumnName($attribute) . sprintf('%+d', $delta))],
                $condition
            );
        }
    }

    /**
     * @param array $condition
     */
    protected function applyTreeAttributeCondition(&$condition)
    {
        if ($this->treeAttribute !== false) {
            $condition = [
                'and',
                $condition,
                [$this->treeAttribute => $this->getAttribute($this->treeAttribute)]
            ];
        }

    }
}