<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 28.05.2018
 * Time: 15:22
 */

namespace common\widgets\ElastiSlide;
use common\widgets\ElastiSlide\assets\ElastiSlideAsset;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

class ElastiSlideWidget extends Widget {
    const ORIENTATION_HORIZONTAL = 'horizontal';
    const ORIENTATION_VERTICAL = 'vertical';
    const DEFAULT_CLIENT_OPTIONS = [
        'orientation' => self::ORIENTATION_HORIZONTAL,
        'transition' => 'ease-in-out',
        'minItems' => 4,
        'imgSizeItemSelector' => 'img',
        'start' => 0,
        'autoSlide' => false,
        'delayTime' => 3000,
    ];
    
    const DEFAULT_CLIENT_EVENTS = [
        'onClick' => 'function( el, position, evt ) { evt.preventDefault(); return false; }',
        'onReady' => 'function() { return false; }',
        'onBeforeSlide' => 'function() { return false; }',
        'onAfterSlide' => 'function() { return false; }',
    ];
    
    /**
     * @var array the options for the ElastiSlide plugin.
     * Please refer to the ElastiSlide plugin Web page for possible options.
     * @see https://tympanus.net/codrops/2011/09/12/elastislide-responsive-carousel/
     */
    public $clientOptions = [];
    
    /**
     * @var array the event handlers for the ElastiSlide plugin.
     * Please refer to the [ElastiSlide plugin](https://tympanus.net/codrops/2011/09/12/elastislide-responsive-carousel/)
     * for information about their callbacks.
     */
    public $clientEvents = [];
    
    /**
     * @var array the HTML attributes for the plugin container tag (default ul) and extra options for \yii\helpers\Html::ul() helper.
     * @see \yii\helpers\Html::ul() for details.
     */
    public $options = [];
    
    /**
     * @var array The array of items that compound the gallery. The syntax is as follows:
     *
     * - src: string, the image URL to display
     * - url: string, the image link href (default $src)
     * - options: HTML attributes of the li tag
     * - linkOptions: HTML attributes of the link
     * - imageOptions: HTML attributes of the image to be displayed
     */
    public $items = [];
    
    /**
     * @var array|callable, the HTML attributes for the `li` tags. This option is ignored if the `item` option is specified
     */
    public $itemOptions = [];
    
    /**
     * @var array|callable, the HTML attributes for the items links. This option is ignored if the `item` option is specified
     */
    public $itemLinkOptions = [];
    
    /** @var array|callable, the HTML attributes for the items image. This option is ignored if the `item` option is specified */
    public $itemImageOptions = [];
    /**
     * @var bool load default css file
     */
    public $defaultCss = true;
    
    /**
     *
     */
    public function init() {
        parent::init();
        
        $this->clientOptions = ArrayHelper::merge(static::DEFAULT_CLIENT_OPTIONS, $this->clientOptions);
        $this->clientEvents = ArrayHelper::merge(static::DEFAULT_CLIENT_EVENTS, $this->clientEvents);
        
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        Html::addCssClass($this->options, 'elastislide-list');
        foreach($this->clientEvents as $key => $event) {
            if(!($event instanceof JsExpression)) {
                $this->clientOptions[$key] = new JsExpression($event);
            }
        }
        
    }
    
    /**
     * @return null
     */
    public function run() {
        if (empty($this->items)) {
            return null;
        }
        echo $this->renderItems();
        $this->registerClientScript();
        
        return null;
    }
    
    /**
     * @return string the items that are need to be rendered.
     */
    public function renderItems()
    {
        if (!isset($this->options['item'])) {
            $this->options['item'] = function($item, $index) {
                if (is_callable($this->itemOptions)) {
                    $itemOptions = call_user_func($this->itemOptions, $item, $index);
                } else {
                    $itemOptions = $this->itemOptions;
                }
                $tag = ArrayHelper::getValue($item, 'tag', 'li');
                return Html::tag($tag, $this->renderItem($item, $index), $itemOptions);
            };
        }
        
        return Html::ul($this->items, $this->options);
    }

    /**
     * @param mixed $item
     * @return null|string the item to render
     */
    public function renderItem($item, $index)
    {
        if (is_string($item)) {
            return Html::a(Html::img($item, $this->itemImageOptions), $item, $this->itemLinkOptions);
        }
        
        if (is_callable($this->itemImageOptions)) {
            $imageOptions = call_user_func($this->itemImageOptions, $item, $index);
            $imageOptions = ArrayHelper::merge($imageOptions, ArrayHelper::getValue($item, 'imageOptions', $this->itemImageOptions));
        } else {
            $imageOptions = ArrayHelper::getValue($item, 'imageOptions', $this->itemImageOptions);
        }
        if (is_callable($this->itemLinkOptions)) {
            $linkOptions = call_user_func($this->itemLinkOptions, $item, $index);
            $linkOptions = ArrayHelper::merge($linkOptions, ArrayHelper::getValue($item, 'linkOptions', $this->itemLinkOptions));
        } else {
            $linkOptions = ArrayHelper::getValue($item, 'linkOptions', $this->itemLinkOptions);
        }
        
        $src = ArrayHelper::getValue($item, 'src');
        if ($src === null) {
            return null;
        }
        $url = ArrayHelper::getValue($item, 'url', $src);
        
        return Html::a(Html::img($src, $imageOptions), $url, $linkOptions);
    }

    /**
     * Registers the client script required for the plugin
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        ElastiSlideAsset::register($view);

        $id = $this->options['id'];
        $options = Json::encode($this->clientOptions);
        $js = "$('#$id').elastislide($options);";
        $view->registerJs($js);
    }
}