<?php
/**
 * Трейт поддержки произвольных хуков в наследуемых классах.
 * @package evas-php\evas-base
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Base\Help;

trait HooksTrait
{
    /**
     * Вызов метода при наличии. Для хуков событий.
     * @param string имя метода
     * @param mixed аргумент метода
     */
    protected function hook(string $methodName, ...$methodArgs)
    {
        if (defined('EVAS_DEBUG') && true == EVAS_DEBUG) {
            echo $methodName . ('cli' == PHP_SAPI ? "\n" : '<br>');
        }
        if (method_exists($this, $methodName)) {
            call_user_func_array([$this, $methodName], $methodArgs);
        }
    }
}
