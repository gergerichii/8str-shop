<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 06.06.2018
 * Time: 11:11
 */

namespace common\widgets;
use kartik\tabs\TabsX;
use yii\bootstrap\Dropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class AdaptiveTabs
 *
 * @package common\widgets
 *
 *          TODO: Доделать!!!
 */
class AdaptiveTabs extends TabsX {
    
    public $tabsCss = '';
    public $collapseCss = '';
    public $collapsePanelHeadingOptions = [];
    public $collapsePanelOptions = [];
    public $respondToWidth = 992;
    
    
    public function initWidget() {
        parent::initWidget();
        Html::addCssClass($this->containerOptions, 'adaptive-tabs');
        $thisClass = (new \ReflectionClass($this))->getShortName();
        if ($this->tabsCss === false) {
            $this->tabsCss = '';
        } elseif (empty($this->tabsCss)) {
            $this->tabsCss = file_get_contents(
                __DIR__ . DIRECTORY_SEPARATOR
                . $thisClass
                . DIRECTORY_SEPARATOR . 'tabs.css'
            );
        } else {
            $this->tabsCss = preg_replace("#^\s*<style\>(.+?)</style\>#is", '\1', $this->tabsCss);
        }
        if ($this->collapseCss === false) {
            $this->collapseCss = '';
        } elseif (empty($this->collapseCss)) {
            $this->collapseCss = file_get_contents(
                __DIR__ . DIRECTORY_SEPARATOR
                . $thisClass
                . DIRECTORY_SEPARATOR . 'collapse.css'
            );
        } else {
            $this->collapseCss = preg_replace("#^\s*<style\>(.+?)</style\>#is", '\1', $this->collapseCss);
        }
        $rTabs = $this->respondToWidth;
        $rCollapse = $this->respondToWidth-1;
        $css = "@media (min-width: {$rTabs}px) {\n{$this->tabsCss}\n}\n";
        $css .= "@media (max-width: {$rCollapse}px) {\n{$this->collapseCss}\n}\n";
        $this->view->registerCss($css);
    }
    
    /**
     * Renders tab items as specified in [[items]].
     *
     * @return string the rendering result.
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     */
    protected function renderItems()
    {
        $headers = $panes = $labels = [];

        if (!$this->hasActiveTab() && !empty($this->items)) {
            $this->items[0]['active'] = true;
        }

        foreach ($this->items as $n => $item) {
            if (!ArrayHelper::remove($item, 'visible', true)) {
                continue;
            }
            $label = $this->getLabel($item);
            $headerOptions = array_merge($this->headerOptions, ArrayHelper::getValue($item, 'headerOptions', []));
            $linkOptions = array_merge($this->linkOptions, ArrayHelper::getValue($item, 'linkOptions', []));
            $content = ArrayHelper::getValue($item, 'content', '');
            
            if (isset($item['items'])) {
                foreach ($item['items'] as $subItem) {
                    $subLabel = $this->getLabel($subItem);
                    $labels[] = $this->printHeaderCrumbs ? $label . $this->printCrumbSeparator . $subLabel : $subLabel;
                }
                $label .= ' <b class="caret"></b>';
                Html::addCssClass($headerOptions, 'dropdown');
                if ($this->renderDropdown($n, $item['items'], $panes)) {
                    Html::addCssClass($headerOptions, 'active');
                }
                Html::addCssClass($linkOptions, 'dropdown-toggle');
                $linkOptions['data-toggle'] = 'dropdown';
                $header = Html::a($label, "#", $linkOptions) . "\n"
                    . Dropdown::widget([
                        'items' => $item['items'],
                        'clientOptions' => false,
                        'view' => $this->getView()
                    ]);
            } else {
                $labels[] = $label;
                $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
                $options['id'] = ArrayHelper::getValue($options, 'id', $this->options['id'] . '-tab' . $n);
                $css = 'tab-pane';
                $isActive = ArrayHelper::remove($item, 'active');
                $contentPanelOptions = ['class' => 'collapse', 'id' => "{$options['id']}-collapse"];
                if ($this->fade) {
                    $css = $isActive ? "{$css} fade in" : "{$css} fade";
                }
                Html::addCssClass($options, $css);
                if ($isActive) {
                    Html::addCssClass($options, 'active');
                    Html::addCssClass($headerOptions, 'active');
                    Html::addCssClass($contentPanelOptions, 'in');
                }
                if (isset($item['url'])) {
                    $header = Html::a($label, $item['url'], $linkOptions);
                } else {
                    $linkOptions['data-toggle'] = 'tab';
                    $linkOptions['role'] = 'tab';
                    $header = Html::a($label, '#' . $options['id'], $linkOptions);
                }
                if ($this->renderTabContent) {
                    Html::addCssClass($options, 'panel');
                    $collapseLinkOptions = $linkOptions;
                    Html::addCssClass($collapseLinkOptions, "collapse-toggle");
                    $collapseLinkOptions = ArrayHelper::merge($collapseLinkOptions, [
                        'data-toggle' => "collapse",
                        'data-parent' => "#{$this->containerOptions['id']} .tab-content",
                    ]);
                    
                    $panes[] =
                            Html::tag(
                                'div',
                                Html::tag(
                                    'div',
                                    Html::tag(
                                        'div',
                                        Html::a(
                                            $label,
                                            "#{$options['id']}-collapse",
                                            $collapseLinkOptions
                                        ),
                                    ['class' => 'panel-tittle']
                                ),
                                ['class' => 'panel-heading']
                            )
                            . Html::tag('div', $content, $contentPanelOptions),
                            $options
                        );
                }
            }
            $headers[] = Html::tag('li', $header, $headerOptions);
        }
        $outHeader = Html::tag('ul', implode("\n", $headers), $this->options);
        if ($this->renderTabContent) {
            $outPane = Html::beginTag('div', ['class' => 'tab-content' . $this->getCss('printable', $this->printable)]);
            foreach ($panes as $i => $pane) {
                if ($this->printable) {
                    $outPane .= Html::tag('div', ArrayHelper::getValue($labels, $i), $this->printHeaderOptions) . "\n";
                }
                $outPane .= "$pane\n";
            }
            $outPane .= Html::endTag('div');
            $tabs = $this->position == self::POS_BELOW ? $outPane . "\n" . $outHeader : $outHeader . "\n" . $outPane;
        } else {
            $tabs = $outHeader;
        }
        return Html::tag('div', $tabs, $this->containerOptions);
    }
    
}