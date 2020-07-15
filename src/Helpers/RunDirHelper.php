<?php
/**
 * @package evas-php\evas-base
 */
namespace Evas\Base\Helpers;

/**
 * Хелпер для директории запуска.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
class RunDirHelper
{
    /**
     * Вспомогательный метод для приведения директории к нормальному виду.
     * @param string путь
     * @return string
     */
    public static function prepareDir(string $dir): string
    {
        return realpath(str_replace('\\', '/', $dir)) . '/';
    }

    /**
     * Получение директории запуска приложения.
     * @return string
     */
    public static function getDir(): string
    {
        static $dir = null;
        if (null === $dir) {
            $dir = static::prepareDir(dirname($_SERVER['SCRIPT_FILENAME']));
        }
        return $dir;
    }
}
