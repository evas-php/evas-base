<?php
/**
 * @package evas-php\evas-base
 */
namespace Evas\Base;

use Evas\Base\Exception\FileNotFoundException;
use Evas\Base\LoadTrait;

/**
 * Трейт расширения приложения подгрузкой файлов.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
trait AppLoadTrait
{
    /**
     * Подключаем трейт подгрузки файлов.
     */
    use LoadTrait;

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
