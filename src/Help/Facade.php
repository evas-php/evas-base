<?php
/**
 * Халпер фасадов.
 * @package evas-php\evas-base
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Base\Help;

use Evas\Base\Help\HooksTrait;

class Facade
{
    /**
     * Подключаем трейт хуков.
     */
    use HooksTrait;

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
                'Facade entity %s not has method %s', __CLASS__, $name
            ));
        }
        if (!static::$mountedObject) {
            static::staticHook('mountDefault');
        }
        if (!static::$mountedObject) {
            throw new \BadMethodCallException(sprintf('Facade %s not has entity', __CLASS__));
        }
        return static::$mountedObject->$name(...$args ?? []);
    }
}
