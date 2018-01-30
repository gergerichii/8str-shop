<?php

namespace common\modules\files;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\web\UrlManager;

/**
 * file module definition class
 *
 * @property string $productImagesPath
 * @property string $productThumbnailsPath
 *
 * TODO: Доделать чтобы все пути можно было настраивать в конфиге
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    const PRODUCTS_RELATIVE_PATH = 'products';
    const IMAGES_RELATIVE_PATH = 'images';
    const THUMBNAIL_RELATIVE_PATH = 'images/thumbnails';
    private $_defaultFilesPath = '@commonFiles';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'common\modules\files\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap ($app)
    {
        $urlManagers = [];
        foreach (array_keys($app->components) as $componentName) {
            if (strPos($componentName, 'UrlManager') > 0)
                $urlManagers[] = $componentName;
        }

        $rules = [
            [
                'name' => 'fileRule',
                'pattern' => 'files/<_a:download|upload|.{0}>/<filePath:[\w\-\.,/_]*>',
                'route' => 'files/default/<_a>',
                'defaults' => [
                    'filePath' => '',
                    '_a' => 'download',
                ]
            ]
        ];
        if (count($urlManagers)) {
            foreach ($urlManagers as $urlManager) {
                /** @var UrlManager $urlManager */
                $urlManager = $app->get($urlManager);
                $urlManager->addRules($rules);
            }
        } else {
            $app->urlManager->addRules($rules);
        }
    }
    /** TODO: Позже доделать */
    public function getProductThumbnailsPath() {
        return $this->getProductImagesPath();
    }

    /**
     * @return string
     */
    public function getProductImagesPath() : string {
        return isset(\Yii::$app->params['productsImagesPath'])
            ? \YII::getAlias(\Yii::$app->params['productsImagesPath'])
            : \YII::getAlias($this->_defaultFilesPath)
            . DIRECTORY_SEPARATOR . self::PRODUCTS_RELATIVE_PATH
            . DIRECTORY_SEPARATOR . self::IMAGES_RELATIVE_PATH;
    }

    public function getFilePath($filename) {
        return \yii::getAlias($this->_defaultFilesPath) . DIRECTORY_SEPARATOR . $filename;
    }

    public function getFileRedirectUri($filePath) {
        return \yii::getAlias("@commonFilesUri/$filePath");
    }

    /**
     * @param $fileName
     *
     * @return string
     */
    public function getProductImagePath($fileName) {
        return $this->getProductImagesPath() . DIRECTORY_SEPARATOR . $fileName;
    }

    /**
     * @param string $imageName
     *
     * @return string
     */
    public static function getImageUri (string $imageName) {
        $filePath = self::PRODUCTS_RELATIVE_PATH . DIRECTORY_SEPARATOR
            . self::IMAGES_RELATIVE_PATH . DIRECTORY_SEPARATOR ;
        return \Yii::$app->urlManager->createUrl(['/files/default/download', 'filePath' => $filePath . $imageName]);
    }
}
