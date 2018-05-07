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
            foreach($this->parseRules($counter->included_pages) as $patterns) {
                list($domainPattern, $pagePattern) = $patterns;
                $ret = $ret || (preg_match($domainPattern, $currentDomain) && preg_match($pagePattern, $currentPage));
            }
        } else {
            $ret = true;
        }
        if ($ret && !empty($counter->excluded_pages)) {
            foreach($this->parseRules($counter->excluded_pages) as $patterns) {
                list($domainPattern, $pagePattern) = $patterns;
                $ret = $ret && !(preg_match($domainPattern, $currentDomain) && preg_match($pagePattern, $currentPage));
            }
        }
        
        return (bool) $ret;
    }
    
    /**
     * @param string $rules
     *
     * @return array
     */
    public function parseRules(string $rules): array {
        $ret = [];
        
        foreach(explode(';', $rules) as $page) {
            $page = trim($page);
            if(strpos($page, ':')) {
                list($domain, $page) = explode(':', $page);
            } else {
                $domain = '*.*.*';
            }
            
            /** подготовка шаблона для домена */
            $prefix = '';
            if ($domain[0] === '*') {
                $prefix = '.*?';
                $domain = substr($domain, 1);
                if ($domain[0] === '.') {
                    $prefix = "^(?:{$prefix}\.)?";
                    $domain = substr($domain, 1);
                }
            }
            preg_match('#(?:(?:(?P<d3>.*?)\.)?(?P<d2>[^\.]+)\.)?(?P<d1>[^\.]+)$#', $domain, $m1);
            $domain = '';
            $predEmpty = true;
            foreach($m1 as $name => $pattern) {
                if (preg_match('#^d(?P<n>\d)$#', $name, $m2)) {
                    /* точка в конце не ставится у домена первого уровня */
                    if (empty($pattern) || $pattern == '*') {
                        if (intval($m2['n']) > 1) {
                            if ($predEmpty) {
                                $domain = "(?:{$domain}[^\.]+\.)?";
                            } else {
                                $domain .= '[^\.]+\.';
                            }
                        } else {
                            $domain .= '.*';
                        }
                    } else {
                        $predEmpty = false;
                        $d = ($m2['n'] !== '1') ? '\.' : '';
                        $pattern = str_replace('.', '\.', $pattern);
                        $domain .= str_replace('*', '[^\.]+', $pattern) . $d;
                    }
                }
            }
            $domain = "#{$prefix}{$domain}$#";
            
            /** Подготовка шаблона для страницы */
            if (empty($page) || $page === '*') {
                $page = '.*';
            } else {
                $page = str_replace('.', '\.', $page);
                $page = str_replace('*', '.*', $page);
            }
            $page = "#^{$page}$#";
            
            $ret[] = [$domain, $page];
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