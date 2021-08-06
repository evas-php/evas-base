<?php
/**
 * Халпер фасадов.
 * @package evas-php\evas-base
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Base\Help;

class Facade
{
    /** @static static объект обёрнутый в фасад */
    protected static $latestObject;

    /**
     * Конструктор.
     */
    public function __construct()
    {
        static::switch($this);
    }

    /**
     * Переключение содержимого фасада.
     * @param static объект
     */
    public static function switch(Facade &$object)
    {
        static::$latestObject = &$object;
    }

    /**
     * Магический вызов метода объекта в фасаде.
     * @param string имя метода
     * @param array|null аргументы
     * @return mixed результат выполнения метода
     * @throws \BadMethodCallException
     */
    public function __call(string $name, array $args = null)
    {
        if (!method_exists(static::class, $name)) {
            throw new \BadMethodCallException(sprintf(
                'Facade entity %s not has method %s', __CLASS__, $name
            ));
        }
        static::switch($this);
        return $this->$name(...$args ?? []);
    }

    /**
     * Магический статический вызов метода объекта в фасаде.
     * @param string имя метода
     * @param array|null аргументы
     * @return mixed результат выполнения метода
     * @throws \BadMethodCallException
     */
    public static function __callStatic(string $name, array $args = null)
    {
        if (!method_exists(static::class, $name)) {
            throw new \BadMethodCallException(sprintf(
                'Facade entity %s not has method %s', __CLASS__, $name
            ));
        }
        if (!static::$latestObject) {
            throw new \BadMethodCallException(sprintf('Facade %s not has entity', __CLASS__));
        }
        return static::$latestObject->$name(...$args ?? []);
    }
}
