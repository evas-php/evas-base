<?php
/**
 * Хелпер для php.
 * @package evas-php\evas-base
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Base\Help;

class PhpHelp
{
    /**
     * Преобразование пути в совместимый с ОС путь.
     * @param string путь
     * @return string путь совместимый с ОС
     */
    public static function path(string $path): string {
        $path = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
        if (is_dir($path) && DIRECTORY_SEPARATOR !== $path[mb_strlen($path) - 1]) {
            $path .= DIRECTORY_SEPARATOR;
        }
        return $path;
    }

    /**
     * * Преобразование пути в совместимый с ОС реальный путь.
     * @param string путь
     * @return string реальный путь совместимый с ОС
     */
    public static function pathReal(string $path): string {
        return static::path(realpath($path));
    }

    /**
     * Проверка значения на ассоциативный массив.
     * @param mixed значение
     * @return bool
     */
    public static function isAssoc($arr): bool
    {
        return is_array($arr) && array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Проверка значения на не ассоциативный массив.
     * @param mixed значение
     * @return bool
     */
    public static function isNotAssoc($arr): bool
    {
        return !static::isAssoc($arr);
    }

    /**
     * Проверка значения на нумерованный массив.
     * @param mixed значение
     * @return bool
     */
    public static function isNumericArray($arr): bool
    {
        return is_array($arr) && !static::isAssoc($arr);
    }

    /**
     * Получение типа массива.
     * @param array массив
     * @return string
     */
    public static function getArrayType(array $arr): string
    {
        return (static::isAssoc($arr) ? 'assoc' : 'numeric') . ' array';
    }

    /**
     * Получение типа переменной с дополнительной расшифровкой.
     * @param mixed переменная
     * @param bool поддерживать ли типы массива
     * @return string тип
     */
    public static function getType($var, bool $arrayTypesAvailable = false): string
    {
        return is_object($var) ? get_class($var) : (
            is_array($var) && $arrayTypesAvailable 
                ? static::getArrayType($var) : gettype($var)
        );
    }

    /**
     * Проверка запуска php из командной строки.
     * @return bool
     */
    public static function isCli(): bool
    {
        return (defined('PHP_SAPI') && substr(PHP_SAPI, 0, 3) == 'cli')
         || (function_exists('php_sapi_name') && php_sapi_name() == 'cli');
    }

    /**
     * Получение конца строки для веб или cli.
     * @return string
     */
    public static function eol(): string
    {
        return static::isCli() ? "\n" : '<br>';
    }
}
