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
     * Вызов метода при наличии.
     * @param string имя метода
     * @param mixed аргумент метода
     */
    protected function hook(string $methodName, ...$methodArgs)
    {
        if (method_exists($this, $methodName)) {
            if (defined('EVAS_DEBUG') && true == EVAS_DEBUG) {
                echo '[hook]: ' . __CLASS__ . '->' . $methodName . ('cli' == PHP_SAPI ? "\n" : '<br>');
            }
            call_user_func_array([$this, $methodName], $methodArgs);
        }
    }

    /**
     * Вызов статического метода при наличии.
     * @param string имя метода
     * @param mixed аргумент метода
     */
    protected static function staticHook(string $methodName, ...$methodArgs)
    {
        if (method_exists(static::class, $methodName)) {
            if (defined('EVAS_DEBUG') && true == EVAS_DEBUG) {
                echo '[staticHook]: ' . __CLASS__ . '::' . $methodName . ('cli' == PHP_SAPI ? "\n" : '<br>');
            }
            call_user_func_array([static::class, $methodName], $methodArgs);
        }
    }
}
