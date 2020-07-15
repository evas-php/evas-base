<?php
/**
 * @package evas-php\evas-base
 */
namespace Evas\Base\Helpers;

/**
 * Кастомизация базового php.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
class PhpHelper
{
    /**
     * Проверка массива на ассоциативность.
     * @return bool
     */
    public static function isAssoc($arr): bool
    {
        return is_array($arr) && array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Проверка на не ассоциативный массив.
     * @return bool
     */
    public static function isNotAssoc($arr): bool
    {
        return !static::isAssoc($arr);
    }

    /**
     * Проверка на нумерованный массив.
     * @return bool
     */
    public static function isNumericArray($arr): bool
    {
        return is_array($arr) && !static::isAssoc($arr);
    }
}
