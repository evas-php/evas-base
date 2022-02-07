<?php
/**
 * Халпер фасадов.
 * @package evas-php\evas-base
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Base\Help;

class Facade
{
    /** @static static объект монтированный в фасад */
    protected static $mountedObject;

    /**
     * Конструктор.
     */
    public function __construct()
    {
        static::mount($this);
    }

    /**
     * Монтирование содержимого фасада.
     * @param static объект
     */
    public static function mount(Facade &$object)
    {
        static::$mountedObject = &$object;
    }

    /**
     * Монтирование содержимого фасада по умолчанию.
     * @throws \InvalidArgumentException
     */
    protected static function mountDefault()
    {}

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
        static::mount($this);
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
                'Facade entity %s not has static method %s', __CLASS__, $name
            ));
        }
        if (!static::$mountedObject) {
            static::mountDefault();
        }
        if (!static::$mountedObject) {
            throw new \BadMethodCallException(sprintf('Facade %s not has entity', __CLASS__));
        }
        return static::$mountedObject->$name(...$args ?? []);
    }
}
