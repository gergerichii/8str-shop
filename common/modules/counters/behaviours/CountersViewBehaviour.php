<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11.04.2018
 * Time: 16:33
 */

namespace common\modules\counters\behaviours;

use common\modules\counters\models\Counters;
use yii\base\Behavior;
use yii\base\ErrorException;
use yii\web\View;

class CountersViewBehaviour extends Behavior {
    
    /**
     * @var array the registered HTML code blocks
     * @see registerHtml()
     */
    private $_html = [];
    
    
    /**
     * @return array
     */
    public function events() {
        return [
            View::EVENT_END_PAGE => 'endPage',
        ];
    }
    
    /**
     *
     * @throws \yii\base\ErrorException
     */
    public function endPage() {
        if (\Yii::$app->request->isAjax || \Yii::$app->request->isPjax)
            return true;
        
        $content = ob_get_clean();
        
        $counters = Counters::find()->active()->all();
        foreach ($counters as $counter) {
            if ($this->showCounter($counter))
                $this->registerHtml($counter->value, $counter->position);
        }
        
        ob_start();
        $ret = strtr($content, [
            View::PH_BODY_BEGIN => View::PH_BODY_BEGIN . "\n" . $this->renderBodyBeginHtml(),
            View::PH_BODY_END => $this->renderBodyEndHtml() . "\n" . View::PH_BODY_END,
        ]);
        
        $this->clear();
        
        echo $ret;
        return true;
    }
    
    /**
     * @return string
     */
    protected function renderBodyBeginHtml() {
        $ret = '';
        if (!empty($this->_html[View::POS_BEGIN])) {
            $ret .= "\n" . implode("\n", $this->_html[View::POS_BEGIN]);
        }
        return $ret;
    }
    
    /**
     * @return string
     */
    protected function renderBodyEndHtml() {
        $ret = '';
        if (!empty($this->_html[View::POS_END])) {
            $ret = implode("\n", $this->_html[View::POS_END]);
        }

        return $ret;
    }
    
    /**
     * @param Counters $counter
     *
     * @return bool
     */
    protected function showCounter($counter) {
        $ret = false;
        $request = \Yii::$app->request;
        $currentDomain = $request->hostName;
        $currentPage = substr($request->url, 1);
        
        if ($request->remoteIP === '195.19.215.184')
            return false;
        
        if (!empty($counter->included_pages)) {
            foreach(explode(';', $counter->included_pages) as $page) {
                $page = trim($page);
                if(strpos($page, ':')) {
                    list($domain, $page) = explode(':', $page);
                } else {
                    $domain = '*';
                }
                
                $ret |= $this->checkShow($currentDomain, $domain) && $this->checkShow($currentPage, $page);
            }
        } else {
            $ret = true;
        }
        if (!empty($counter->excluded_pages)) {
            foreach(explode(';', $counter->excluded_pages) as $page) {
                $page = trim($page);
                if(strpos($page, ':')) {
                    list($domain, $page) = explode(':', $page);
                } else {
                    $domain = '*';
                }
                
                $ret &= !($this->checkShow($currentDomain, $domain) && $this->checkShow($currentPage, $page));
            }
        }
        
        return $ret;
    }
    
    /**
     * @param $source
     * @param $mask
     *
     * @return bool
     */
    protected function checkShow($source, $mask) {
        if($mask[0] === '*') {
            $mask = substr($mask, 1);
            if (empty($mask)) {
                $ret = true;
            } elseif ($mask[-1] === '*') {
                $mask = substr($mask, 0, -1);
                $ret = strpos($source, $mask) !== false;
            } else {
                $ret = substr($source, strlen($source) - strlen($mask) - 1) === $mask;
            }
        } elseif ($mask[-1] === '*') {
            $mask = substr($mask, 0, -1);
            $ret = substr($source, 0, strlen($mask)) === $mask;
        } else {
            $ret = $source === $mask;
        }
        
        return $ret;
    }
    
    /**
     * Registers a HTML code block.
     *
     * @param string $html     the HTML code block to be registered
     * @param int    $position the position at which the JS script tag should be inserted
     *                         in a page. The possible values are:
     *
     * - [[POS_BEGIN]]: at the beginning of the body section
     * - [[POS_END]]: at the end of the body section
     * - [[POS_LOAD]], [[POS_READY]] and [[POS_HEAD]]: is unacceptable
     *
     * @param string $key      the key that identifies the HTML code block. If null, it will use
     *                         $html as the key. If two HTML code blocks are registered with the same key, the latter
     *                         will overwrite the former.
     *
     * @throws \yii\base\ErrorException
     */
    public function registerHtml($html, $position = View::POS_HEAD, $key = null)
    {
        $key = $key ?: md5($html);
        $this->_html[$position][$key] = $html;
        if ($position === View::POS_READY || $position === View::POS_LOAD || $position === View::POS_HEAD) {
            throw new ErrorException('Для HTML блока позиции POS_READY, POS_LOAD и self::POS_HEAD недоступны');
        }
    }
    
    /**
     *
     */
    protected function clear() {
        $this->_html = [];
    }
}