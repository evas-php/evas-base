<?php
/**
 * @package evas-php/evas-base
 */
namespace Evas\Base;

use Evas\Base\Exception\FileNotFoundException;

/**
 * Трейт расширения приложения подгрузкой файлов.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
trait AppLoadTrait
{
    /**
     * Получение полного имени файла относительно корня приложения.
     * @param string относительное имя файла
     * @return string олное имя файла
     */
    public static function filename(string $filename): string
    {
        return static::dir() . $filename;
    }

    /**
     * Подгрузить содержимое файла относительно корня приложения или по абсолютному пути.
     * @param string имя файла
     * @param array аргументы для загружаемого файла
     * @param object|null контекст
     * @throws FileNotFoundException
     * @return mixed|null возвращаемый результат файла
     */
    public static function load(string $filename, array $args = [], object &$context = null)
    {
        static::throwIfNotCanLoad($filename);
        $load = function () use ($filename, $args) {
            extract($args);
            return include $filename;
        };
        if ($context) $load = $load->bindTo($context);
        return $load();
    }

    /**
     * Проверка на возможность загрузить файл.
     * @param string имя файла
     * @return bool удалось ли прочитать файл
     */
    public static function canLoad(string $filename): bool
    {
        return is_readable($filename) && is_file($filename) ? true : false;
    }

    /**
     * Выбросить исключение, если не удается загрузить файл.
     * @throws FileNotFoundException
     */
    public static function throwIfNotCanLoad(string $filename)
    {
        if (!static::canLoad($filename)) {
            throw new FileNotFoundException("File \"$filename\" not found");
        }
    }

    /**
     * Подгрузить содержимое файла относительно корня приложения.
     * @param string имя файла
     * @param array аргументы для загружаемого файла
     * @return mixed|null возвращаемый результат файла
     */
    public static function loadByApp(string $filename, array $args = [])
    {
        $filename = static::filename($filename);
        return static::load($filename, $args);
    }

    /**
     * Проверка на возможность загрузить файл относительно корня приложения.
     * @param string имя файла
     * @return bool удалось ли прочитать файл
     */
    public static function canLoadByApp(string $filename): bool
    {
        $filename = static::filename($filename);
        return is_readable($filename) && is_file($filename) ? true : false;
    }
}
