<?php
/**
 * @package evas-php/evas-base
 */
namespace Evas\Base;

/**
 * Хелпер для директории запуска.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
class RunDirHelper
{
    /**
     * Вспомогательный метод для удаления слеша в конце директории.
     * @param string путь
     * @return string
     */
    public static function addEndDirSlash(string $dir): string
    {
        return str_replace('\\', '/', realpath($dir)) . '/';
    }

    /**
     * Получение директории запуска приложения.
     * @return string
     */
    public static function getDir(): string
    {
        static $dir = null;
        if (null === $dir) {
            $dir = static::addEndDirSlash(dirname($_SERVER['SCRIPT_FILENAME']) . '/');
        }
        return $dir;
    }
}
