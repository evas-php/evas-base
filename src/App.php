<?php
/**
 * Класс приложения.
 * @package evas-php\evas-base
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Base;

use Evas\Base\Traits\AppDiTrait;
use Evas\Base\Traits\AppDirTrait;

class App
{
    use AppDirTrait;
    use AppDiTrait;

    /** @var static инстанс приложения */
    public static $instance;

    /**
     * Получение инстанса приложения.
     * @return static
     */
    public static function instance()
    {
        if (!static::$instance) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    /**
     * Установка инстанса приложения.
     * @param App инстанс приложения
     */
    public static function setInstance(App &$instance)
    {
        static::$instance = &$instance;
    }
}
