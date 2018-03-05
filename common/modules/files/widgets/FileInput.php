<?php

namespace common\modules\files\widgets;


use common\modules\files\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class FileInput
 */
class FileInput extends \kartik\widgets\FileInput
{
    /**
     * Entity name
     * @var string
     */
    public $entityType = 'images';

    /**
     * @inheritdoc
     */
    public function init() {
        $options = $this->buildPreviewOptions();
        $this->pluginOptions = ArrayHelper::merge($this->pluginOptions, $options);

        parent::init();
    }

    /**
     * Has multiple
     * @return bool
     */
    private function hasMultiple() {
        $hasMultiple = false;
        if (is_array($this->options) && array_key_exists('multiple', $this->options)) {
            $hasMultiple = (bool)$this->options['multiple'];
        }

        return $hasMultiple;
    }

    /**
     * For building preview options
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    private function buildPreviewOptions() {
        if (!$this->hasMultiple()) {
            return [];
        }

        $files = [];
        if ($this->model->hasProperty($this->attribute)) {
            $files = $this->model->{$this->attribute};
        }

        /** @var Module $fileManager */
        $fileManager = \Yii::$app->getModule('files');

        $initialPreview = [];
        $initialPreviewConfig = [];
        $index = 1;
        $urlDelete = Url::to(['/catalog/default/delete-image']);
        foreach ($files as $file) {
            $image = $fileManager->createEntity($this->$entityType, $file);
            if (!$image->exists()) {
                continue;
            }

            $initialPreview[] = $image->getUri(true);
            $initialPreviewConfig[] = [
                'caption' => $image->getBasename(),
                'size' => $image->getSize(),
                'url' => $urlDelete,
                'key' => $index,
                'extra' => [
                    // TODO
                    'id' => $this->model->id,
                    'imageName' => $image->getBasename()
                ]
            ];

            $index++;
        }

        $this->attribute = $this->attribute . '[]';

        return [
            'initialPreview' => $initialPreview,
            'initialPreviewAsData' => true,
            'initialPreviewConfig' => $initialPreviewConfig,
            'overwriteInitial' => false,
            'showUpload' => false,
            'maxFileSize' => 2800,
        ];
    }
}