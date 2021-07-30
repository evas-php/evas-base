<?php
/**
 * Трейт для подключения файлов.
 * @package evas-php\evas-base
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Base\Traits;

use Evas\Base\App;
use Evas\Base\Exceptions\FileNotFoundException;

trait IncludeTrait
{
    /**
     * Загрузка файла.
     * @param string путь файла
     * @param array аргументы для загружаемого файла
     * @param object|null контекст файла
     * @return mixed|null возвращаемый результат файла
     * @throws FileNotFoundException
     */
    public static function include(string $filename, array $args = null, object &$context = null)
    {
        $filename = App::resolveByApp($filename);
        static::throwIfNotCanInclude($filename);
        $load = function () use ($filename, $args) {
            if (!empty($args)) extract($args);
            return include $filename;
        };
        if ($context) $load = $load->bindTo($context);
        return $load();
    }

    /**
     * Проверка наличия файла.
     * @param string путь файла
     * @return bool найден ли файл
     */
    public function canInclude(string $filename): bool
    {
        $filename = App::resolveByApp($filename);
        return is_readable($filename) && is_file($filename);
    }

    /**
     * Выбросить исключение, если файл не найден.
     * @param string путь файла
     * @throws FileNotFoundException
     */
    public function throwIfNotCanInclude(string $filename)
    {
        if (!static::canInclude($filename)) {
            throw new FileNotFoundException($filename);
        }
    }
}
