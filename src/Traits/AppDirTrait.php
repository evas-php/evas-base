<?php
/**
 * Трейт поддержки базовой директории для приложения.
 * @package evas-php\evas-base
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Base\Traits;

use Evas\Base\Exceptions\DirectoryNotFoundException;
use Evas\Base\Help\PhpHelp;
use Evas\Base\Loader;

include_once __DIR__ .'/../Help/PhpHelp.php';
// include_once __DIR__ .'/../Help/functions.php';

trait AppDirTrait
{
    /**
     * Установка директории приложения.
     * @param string директория
     */
    public static function setDir(string $dir)
    {
        $dir = PhpHelp::pathReal($dir);
        if (!is_dir($dir)) {
            throw new DirectoryNotFoundException($dir);
        }
        return static::set('dir', $dir);
    }

    /**
     * Получение директории приложения.
     * @return string директория
     */
    public static function dir(): string
    {
        return static::get('dir', Loader::getVendorParentDir(), true);
    }

    /**
     * Получение авбсолютного пути для пути относительно приложения.
     * @param string путь относительно приложения
     * @return string абсолютный путь
     */
    public static function absolutePathByApp(string $path): string
    {
        return PhpHelp::path(static::isAbsoluteByApp($path) ? $path : static::dir().$path);
    }

    /**
     * Получение пути относительно приложения для абсолютного пути.
     * @param string абсолютный путь
     * @return string|null путь относительно приложения или null
     */
    public static function relativePathByApp(string $path): ?string
    {
        return PhpHelp::path(static::isAbsoluteByApp($path)
            ? substr($path, strlen(static::dir())) : $path);
    }

    /**
     * Является ли путь абсолютным относительно приложения.
     * @param string путь
     * @return bool
     */
    public static function isAbsoluteByApp(string $path): bool
    {
        return substr($path, 0, strlen(static::dir())) === static::dir();
    }

    /**
     * Получение абсолютного пути от приложения, если исходный путь не найден.
     * @param string путь
     * @return string путь относильтено приложения
     */
    public static function resolveByApp(string $path): string
    {
        return is_readable($path) ? PhpHelp::path($path) : static::absolutePathByApp($path);
    }
}
