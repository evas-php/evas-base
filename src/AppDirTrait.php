<?php
/**
 * @package evas-php/evas-base
 */
namespace Evas\Base;

use Evas\Base\Helpers\RunDirHelper;

/**
 * Трейт поддежки методов директории приложения.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
trait AppDirTrait
{
    /**
     * Установка базовой директории приложения.
     * @param string
     * @return self
     */
    public static function setDir(string $dir)
    {
        return static::set('dir', RunDirHelper::prepareDir($dir));
    }

    /**
     * Получение базовой директории приложения.
     * @return string
     */
    public static function getDir(): string
    {
        if (!static::has('dir')) {
            static::setDir(static::getRunDir());
        }
        return static::get('dir');
    }

    /**
     * Получение директории запуска приложения.
     * @return string
     */
    public static function getRunDir(): string
    {
        return RunDirHelper::getDir();
    }

    /**
     * Получение/установка базовой директории приложения.
     * @param string|null директория
     * @return string директория
     */
    public static function dir(string $dir = null)
    {
        if ($dir) static::setDir($dir);
        return static::getDir();
    }
}
