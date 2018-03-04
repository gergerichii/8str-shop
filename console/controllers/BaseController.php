<?php

namespace console\controllers;

use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class BaseController
 */
abstract class BaseController extends Controller
{
    /**
     * Success
     * @param string $msg
     */
    protected function success($msg) {
        $msg = $this->ansiFormat($msg, Console::FG_GREEN);
        $this->stdout($msg . "\n");
    }

    /**
     * Notice
     * @param string $msg
     */
    protected function notice($msg) {
        $msg = $this->ansiFormat($msg, Console::FG_CYAN);
        $this->stdout("\t- $msg\n");
    }

    /**
     * Error
     * @param string $msg
     * @param array $errors
     */
    protected function error($msg, $errors = []) {
        $msg = $this->ansiFormat($msg, Console::FG_RED);
        $this->stderr($msg . "\n");
        foreach ((array)$errors as $name => $mess) {
            foreach ((array)$mess as $mes) {
                $this->error("\t-$name: $mes");
            }
        }
    }
}