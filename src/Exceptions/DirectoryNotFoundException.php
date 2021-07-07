<?php
/**
 * Класс исключения не найденной директории.
 * @package evas-php\evas-base
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Base\Exceptions;

use \Throwable;
use Evas\Base\Exceptions\BaseException;

class DirectoryNotFoundException extends BaseException
{
    /**
     * Выбрасывание исключения с генерацией сообщения по имени директории.
     * @param string имя директории
     * @param int код|null ошибки
     * @return static
     */
    public static function byName(string $name, int $code = 0)
    {
        return new static("Directory \"$name\" not found", $code);
    }
}
