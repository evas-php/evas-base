<?php
/**
 * @package evas-php/evas-base
 */
namespace Evas\Base;

use Evas\Base\RunDirHelper;

/**
 * Трейт поддежки методов директории приложения.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
trait AppDirTrait
{
    /**
     * @var string директория приложения
     */
    protected $dir;

    /**
     * Установка базовой директории приложения.
     * @param string
     * @return self
     */
    public static function setDir(string $dir)
    {
        return static::instanceSet('dir', RunDirHelper::addEndDirSlash($dir));
    }

    /**
     * Получение базовой директории приложения.
     * @return string
     */
    public static function getDir(): string
    {
        if (!static::instanceHas('dir')) {
            static::setDir(static::getRunDir());
        }
        return static::instanceGet('dir');
    }

    /**
     * Получение директории запуска приложения.
     * @return string
     */
    public static function getRunDir(): string
    {
        return RunDirHelper::getDir();
    }
}
