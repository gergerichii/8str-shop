<?php

namespace common\base;

use yii\web\Controller;

class BaseFrontendController extends Controller {
    /**
     * @param string $label
     * @param string|array $url
     */
    protected function addBreadcrumb(string $label, $url = null) {
        if (empty($this->view->params['breadcrumbs']))
            $this->view->params['breadcrumbs'] = [];
        $bc['label'] = $label;
        if (!empty($url)) {
            $bc['url'] = $url;
        }
        array_push($this->view->params['breadcrumbs'], $bc);
    }
    
    /**
     * @param array $breadcrumbs
     */
    protected function addBreadcrumbs(array $breadcrumbs){
        foreach ($breadcrumbs as $bc) {
            if (isset($bc['label'])) {
                $this->addBreadcrumb($bc['label'],
                    !empty($bc['url']) ? $bc['url'] : null);
            }
        }
    }
}
