<?php
/**
 * Трейт Di-контейнера приложения.
 * @package evas-php/evas-base
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Base\Traits;

use Evas\Di\Container;
use Evas\Di\Definitions\CreateObject;

trait AppDiTrait
{
    /** @var Container DI-контейнер приложения */
    protected $di;

    /**
     * Установка DI-контейнера приложения.
     * @param Container DI-контейнер
     */
    public static function setDi(Container &$di)
    {
        static::instance()->di = &$di;
    }

    /**
     * Получение DI-контейнера приложения.
     * @return Container DI-контейнер приложения
     */
    public static function di(): Container
    {
        if (!static::instance()->di) {
            $di = new Container;
            static::setDi($di);
        }
        return static::instance()->di;
    }

    /**
     * Установка свойства DI-контейнера.
     * @param string имя свойства
     * @param mixed значение
     */
    public static function set(string $name, $value)
    {
        static::di()->set($name, $value);
        return static::instance();
    }

    /**
     * Проверка наличия свойства DI-контейнера.
     * @param string имя свойства
     * @return bool
     */
    public static function has(string $name): bool
    {
        return static::di()->has($name);
    }

    /**
     * Получение свойства DI-контейнера.
     * @param string имя свойства
     * @param mixed|null значение по умолчанию, если свойства нет
     * @param bool|null установить значение по умолчанию
     * @return mixed значение свойства или по умолчанию
     */
    public static function get(string $name, $default = null, bool $setDefaultIfNotHas = false)
    {
        return static::di()->get($name, $default, $setDefaultIfNotHas);
    }

    /**
     * Вызов свойства DI-контейнера.
     * @param string имя свойства
     * @param array|null аргументы вызова
     * @return mixed результат вызова
     */
    public static function call(string $name, array $args = null)
    {
        return static::di()->call($name, $args);
    }

    public static function __callStatic(string $name, array $args = null)
    {
        if (method_exists(static::instance(), $name)) {
            return static::instance()->$name(...$args);
        }
        if (static::has($name)) {
            return static::di()->isCallable($name)
                ? static::call($name, $args)
                : static::get($name);
        } else {
            throw new \Exception("Not found method \"$name\" in App and App DI container");
        }
    }

    public function __call(string $name, array $args = null)
    {
        return static::__callStatic($name, $args);
    }
}
