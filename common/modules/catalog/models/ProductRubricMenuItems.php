<?php

namespace common\modules\catalog\models;

/**
 * Class ProductRubricMenuItems
 *
 * @author Andriy Ivanchenko <ivanchenko.andriy@gmail.com>
 */
class ProductRubricMenuItems
{
    private $rubrics = [];
    private $callback = null;

    /**
     * ProductRubricMenuItems constructor.
     * 
     * @param array $rubrics
     * @param callable $callback
     */
    public function __construct(array $rubrics, callable $callback) {
        $this->rubrics = $rubrics;
        $this->callback = $callback;
    }

    /**
     * Render menu items
     * 
     * @return array
     */
    public function render() {
        reset($this->rubrics);
        return $this->getTree();
    }

    /**
     * Get menu items as tree
     * 
     * @param int $level
     * @return array
     */
    private function getTree($level = 0) {
        $childrens = [];
        $childrenLevel = $level + 1;
        $index = 0;
        while ($rubric = current($this->rubrics)) {
            $node = call_user_func($this->callback, $rubric);
            $node['items'] = null;

            // TODO Related level
            if ($rubric->level == $childrenLevel) {
                $childrens[$index] = $node;
                $index++;
                next($this->rubrics);
            } elseif ($rubric->level > $childrenLevel) {
                $childrens[$index - 1]['items'] = $this->getTree($childrenLevel);
            } else {
                break;
            }
        }

        return $childrens;
    }
}