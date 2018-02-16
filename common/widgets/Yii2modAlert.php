<?php
namespace common\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii2mod\notify\BootstrapNotify;

/**
 * Документацию см. в родительском классе.
 *
 * Добавлена возможность выведения нескольких настраиваемых сообщений + вывод сообщений ошибок для моделей
 *
 * Примеры:
 * ```php
 *  Yii::$app->session->setFlash('info', [
 *      [
 *          'icon' => 'glyphicon glyphicon-user',
 *          'title' => '<b>Заголовок Сообщения 1</b>',
 *          'message' => '<p>Сообщение 1</p>',
 *          'url' => 'https://example.com/',
 *          'target' => '_blank'
 *      ]
 *      [
 *          'icon' => 'glyphicon glyphicon-user',
 *          'title' => '<b>Заголовок Сообщения 2</b>',
 *          'message' => '<p>Сообщение 2</p>',
 *          'url' => 'https://example2.com/',
 *          'target' => '_blank'
 *      ]
 *  ]);
 * ```
 *
 * ```php
 * if (!$model->validate()) {
 *      Yii::$app->session->setFlash('modelErrors', $model->errors);
 * }
 * ```
 */
class Yii2modAlert extends BootstrapNotify
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - $key is the name of the session flash variable
     * - $value is the bootstrap alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        'error' => self::TYPE_DANGER,
        'modelErrors' => self::TYPE_DANGER,
        'danger' => self::TYPE_DANGER,
        'success' => self::TYPE_SUCCESS,
        'info' => self::TYPE_INFO,
        'warning' => self::TYPE_WARNING,
    ];
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->useSessionFlash) {
            $session = Yii::$app->getSession();
            $flashes = $session->getAllFlashes();
            foreach ($flashes as $type => $data) {
                if (isset($this->alertTypes[$type])) {
                    if (ArrayHelper::isAssociative($data)) {
                        $data = [$data];
                    }
                    foreach ((array) $data as $key => $messages) {
                        if (ArrayHelper::isAssociative($messages)) {
                            if ($type === 'modelErrors') {
                                $widgetOptions = $this->options;
                                foreach ($messages as $field => $errors) {
                                    foreach ($errors as $error) {
                                        $options = [
                                            'title' => "<b>Ошибка валидации поля '$field'</b>",
                                            'message' => "<p>$error</p>",
                                        ];
                                        $this->options = ArrayHelper::merge($widgetOptions, $options);
                                        $this->clientOptions['type'] = $this->alertTypes[$type];
                                        $this->renderMessage();
                                    }
                                }
                            } else {
                                $this->options = ArrayHelper::merge($this->options, $messages);
                                $this->clientOptions['type'] = $this->alertTypes[$type];
                                $this->renderMessage();
                            }
                        } else {
                            $messages = (array)$messages;
                            foreach ($messages as $i => $message) {
                                $this->options['message'] = $message;
                                $this->clientOptions['type'] = $this->alertTypes[$type];
                                $this->renderMessage();
                            }
                        }
                    }

                    $this->options = [];
                    $session->removeFlash($type);
                }
            }
        } else {
            $this->renderMessage();
        }
    }
}
