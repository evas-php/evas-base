<?php
/**
 * @package evas-php/evas-base
 */
namespace Evas\Base;

/**
 * Трейт расширения приложения подгрузкой файлов.
 * @author Egor Vasyakin <e.vasyakin@itevas.ru>
 * @since 1.0
 */
trait AppLoadTrait
{
    /**
     * Подгрузить содержимое файла относительно корня приложения или по абсолютному пути.
     * @param string имя файла
     * @param bool использовать абсолютный путь
     * @return mixed|null возвращаемый результат файла
     */
    public static function load(string $filename, bool $absolutePath = false)
    {
        if (false === $absolutePath) {
            $filename = static::getDir() . $filename;
        }
        if (is_readable($filename)) {
            return include $filename;
        }
    }

    /**
     * Подгрузить содержимое файла по абсолютному пути.
     * @param string имя файла
     * @return mixed|null возвращаемый результат файла
     */
    public static function loadByRoot(string $filename)
    {
        return static::load($filename, true);
    }

    /**
     * Подгрузить содержимое файла относительно корня приложения.
     * @param string имя файла
     * @return mixed|null возвращаемый результат файла
     */
    public static function loadByApp(string $filename)
    {
        return static::load($filename, false);
    }
}
