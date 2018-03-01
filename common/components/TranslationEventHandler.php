<?php

namespace common\components;

use yii\i18n\MissingTranslationEvent;

/**
 * Class TranslationEventHandler
 */
class TranslationEventHandler
{
    /**
     * Handle missing translation
     * @param MissingTranslationEvent $event
     */
    public static function handleMissingTranslation(MissingTranslationEvent $event) {
        $event->translatedMessage = "@MISSING: {$event->category}.{$event->message} FOR LANGUAGE {$event->language} @";
    }
}