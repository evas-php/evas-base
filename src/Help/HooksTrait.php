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
            evasDebug('[hook]: ' . __CLASS__ . '::' . $methodName);
            return call_user_func_array([$this, $methodName], $methodArgs);
        }
        return null;
    }

    /**
     * Вызов статического метода при наличии.
     * @param string имя метода
     * @param mixed аргумент метода
     */
    protected static function staticHook(string $methodName, ...$methodArgs)
    {
        if (method_exists(static::class, $methodName)) {
            evasDebug('[staticHook]: ' . __CLASS__ . '::' . $methodName);
            return call_user_func_array([static::class, $methodName], $methodArgs);
        }
        return null;
    }
}
