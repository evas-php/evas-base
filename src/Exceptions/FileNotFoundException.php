<?php
/**
 * Класс исключения не найденного файла.
 * @package evas-php\evas-base
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Base\Exceptions;

use \Throwable;
use Evas\Base\Exceptions\BaseException;

class FileNotFoundException extends BaseException
{
    /**
     * Переопределяем базовый конструтор исключения.
     * @param string имя файла
     * @param int|null код ошибки
     * @param Throwable|null предыдущее исключение
     */
    public function __construct(string $name, int $code = 0, Throwable $previous = null)
    {
        $message = "File \"$name\" not found";
        parent::__construct($message, $code, $previous);
    }
}
