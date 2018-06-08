<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 28.05.2018
 * Time: 19:58
 */

namespace common\widgets\ElevateZoom;
use common\widgets\ElevateZoom\assets\ElevateZoomAsset;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

class ElevateZoomWidget extends Widget {
    const DEFAULT_CLIENT_OPTIONS = [
        "zoomActivation" => "hover", // Can also be click (PLACEHOLDER FOR NEXT VERSION)
        "zoomEnabled" => true, //false disables zoomwindow from showing
        "preloading" => 1, //by default, load all the images, if 0, then only load images after activated (PLACEHOLDER FOR NEXT VERSION)
        "zoomLevel" => 1, //default zoom level of image
        "scrollZoom" => false, //allow zoom on mousewheel, true to activate
        "scrollZoomIncrement" => 0.1,  //steps of the scrollzoom
        "minZoomLevel" => false,
        "maxZoomLevel" => false,
        "easing" => false,
        "easingAmount" => 12,
        "lensSize" => 200,
        "zoomWindowWidth" => 400,
        "zoomWindowHeight" => 400,
        "zoomWindowOffetx" => 0,
        "zoomWindowOffety" => 0,
        "zoomWindowPosition" => 1,
        "zoomWindowBgColour" => "#fff",
        "lensFadeIn" => false,
        "lensFadeOut" => false,
        "debug" => false,
        "zoomWindowFadeIn" => false,
        "zoomWindowFadeOut" => false,
        "zoomWindowAlwaysShow" => false,
        "zoomTintFadeIn" => false,
        "zoomTintFadeOut" => false,
        "borderSize" => 4,
        "showLens" => true,
        "borderColour" => "#888",
        "lensBorderSize" => 1,
        "lensBorderColour" => "#000",
        "lensShape" => "square", //can be "round"
        "zoomType" => "window", //window is default,  also "lens" available -
        "containLensZoom" => false,
        "lensColour" => "white", //colour of the lens background
        "lensOpacity" => 0.4, //opacity of the lens
        "lenszoom" => false,
        "tint" => false, //enable the tinting
        "tintColour" => "#333", //default tint color, can be anything, red, #ccc, rgb(0,0,0)
        "tintOpacity" => 0.4, //opacity of the tint
        "gallery" => false,
        "galleryActiveClass" => "zoomGalleryActive",
        "imageCrossfade" => false,
        "constrainType" => false,  //width or height
        "constrainSize" => false,  //in pixels the dimensions you want to constrain on
        "loadingIcon" => false, //http://www.example.com/spinner.gif
        "cursor" => "default", // user should set to what they want the cursor as, if they have set a click function
        "responsive" => true,
    ];
    
    const DEFAULT_CLIENT_EVENTS = [
        "onComplete" => "$.noop",
        "onZoomedImageLoaded" => 'function() {}',
        "onImageSwap" => '$.noop',
        "onImageSwapComplete" => '$.noop'
    ];
    
    public $options = [];
    
    public $items = [];
    /**
     * @var array
     * @see http://www.elevateweb.co.uk/image-zoom/configuration
     */
    public $clientOptions = [];
    public $clientEvents = [];
    
    public function init() {
        $this->clientOptions = ArrayHelper::merge(static::DEFAULT_CLIENT_OPTIONS, $this->clientOptions);
        $this->clientEvents = ArrayHelper::merge(static::DEFAULT_CLIENT_EVENTS, $this->clientEvents);
        
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        foreach($this->clientEvents as $key => $event) {
            if(!($event instanceof JsExpression)) {
                $this->clientOptions[$key] = new JsExpression($event);
            }
        }
    }
    
    public function run() {
        if (empty($this->items)) {
            return null;
        }
        
        echo $this->renderItems();
        $this->registerClientScript();
        
        return null;
        
    }
    
    public function renderItems() {
        if (count($this->items) === 1 || isset($this->clientOptions['gallery'])) {
            $item = array_shift($this->items);
            $options = ArrayHelper::merge($this->options, ['data-zoom-image' => $item['url']]);
            return Html::img($item['url'], $options);
        } else {
            //* TODO: Доделать виджет */
        }
    }
    
    public function registerClientScript() {
        $view = $this->getView();
        ElevateZoomAsset::register($view);

        $id = $this->options['id'];
        $options = Json::encode($this->clientOptions);
        $js = "$('#$id').elevateZoom($options);";
        $view->registerJs($js);
        
    }
}