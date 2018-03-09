<?php

namespace console\controllers;

use common\modules\catalog\models\Product;
use common\modules\files\models\Image;
use common\modules\files\Module as FilesManager;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\image\drivers\Image as DriverImage;

/**
 * Actions with the catalog
 */
class CatalogController extends BaseController
{
    /**
     * Importing (replace) images to products from the @console/images/{product name}.jpg
     *
     * @param string $path Path to file. May be the alias.
     *
     * @return int
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function actionImportImages($path) {

        /** @var FilesManager $filesManager */
        $filesManager = \Yii::$app->getModule('files');
        /** @var Image $image */
        $image = $filesManager->getEntityInstance('products/images');

        $imageNumber = 0;
        $amountOfFoundProduct = 0;

        $path = \Yii::getAlias($path);
        if (!is_dir($path)) {
            $this->error('The path is wrong.');
            return 0;
        }

        $groups = $this->findImages($path);
        
        $f = fopen("{$path}/files.csv", 'w');
        fputcsv($f, array_keys($groups));
        fclose($f);
        
        $this->success('Possible quantity of products: ' . count($groups) . '.');
        
        $amountOfNotFoundProduct = $groups;
        /** @var Product $product */
        foreach(Product::find()->each() as $product) {
            echo '.';
            $productName = preg_replace('#[/]#', '', $product->name);
            if (isset($groups[$productName])) {
                $amountOfFoundProduct++;
                unset($amountOfNotFoundProduct[$productName]);
                // To deleting old images
                if ($product->images) {
                    $groupImages = ArrayHelper::getColumn($groups[$productName], 'basename');
                    foreach ($product->images as $imageName) {
                        $image->fileName = $imageName;
                        if (false !== array_search($imageName, $groupImages) && $image->exists()) {
                            continue;
                        }
                        // Delete old files
                        $image->delete();
                        $product->deleteFile($imageName);
                    }
                }
                // Addition new images
                foreach ($groups[$productName] as $fileInfo) {
                    if (false === $product->hasFile($fileInfo['basename'])) {
                        $product->addFile($fileInfo['basename']);
        
                        $image->fileName = $fileInfo['basename'];
                        if (false === $image->pickFrom($fileInfo['dirname'])) {
                            $this->error('Product #' . $product->id . ' error: ' . $image->getFirstError('') . '.');
                        } else {
                            $image->adaptSize(DriverImage::CROP);
                        }
                    }
    
                    if ($image->exists()) {
                        $imageNumber++;
                        $image->createThumbs();
                    }
                }
    
                $product->update();
            }
        }
        
        if (count($amountOfNotFoundProduct)) {
            $f = fopen("{$path}/notFoundFiles.csv", 'w');
            fputcsv($f, array_keys($amountOfNotFoundProduct));
            fclose($f);
        }
        
        $this->success($imageNumber . ' images have been added. There are ' . $amountOfFoundProduct . ' products found and ' . count($amountOfNotFoundProduct) . ' not found. ' . PHP_EOL);

        return 0;
    }

    /**
     * Find images
     * @param string $path
     * @return array
     */
    private function findImages(string $path) {
        $groups = [];
        $files = FileHelper::findFiles($path, ['only' => ['*.jpg']]);
        if (!$files) {
            $this->error('Images not found.' . PHP_EOL);
            return [];
        }

        $this->success('Found ' . count($files) . ' images.' . PHP_EOL);

        foreach ($files as $fileName) {
            $fileInfo = pathinfo($fileName);
            $productName = $this->getProductName($fileInfo);
            if (array_key_exists($productName, $groups)) {
                $groups[$productName][] = $fileInfo;
            } else {
                $groups[$productName] = [$fileInfo];
            }
        }

        return $groups;
    }

    /**
     * Get product name by image name
     * @param array $fileInfo
     * @return string
     */
    private function getProductName(array $fileInfo) {
        preg_match('/(?<productname>.*)_(?<number>[0-9]{1,2})$/', $fileInfo['filename'], $matches);
        if (array_key_exists('productname', $matches)) {
            return $matches['productname'];
        }

        return $fileInfo['filename'];
    }

    /**
     * Replaces the wrong hyphen for the correct hyphen in all file names in the specified path
     * @param string $path Path to file. May be the alias.
     * @return int
     */
    public function actionHyphen(string $path) {
        $path = \Yii::getAlias($path);
        if (!is_dir($path)) {
            $this->error('The path is wrong.' . PHP_EOL);
            return 0;
        }

        $files = FileHelper::findFiles($path, ['only' => ['*.jpg']]);
        $this->success('Amount of files: ' . count($files) . '.' . PHP_EOL);
        foreach ($files as $oldName) {
            $newName = str_replace(html_entity_decode('&#8209;', ENT_HTML5, 'UTF-8'), '-', $oldName);
            if (!rename($oldName, $newName)) {
                $this->error('Could not rename file ' . $oldName . '.' . PHP_EOL);
            }
        }

        $this->success('Rename is complete.' . PHP_EOL);

        return 0;
    }
}