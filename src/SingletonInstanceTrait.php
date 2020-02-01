<?php
/**
 * @package evas-php/evas-base
 */
namespace Evas\Base;

use Evas\Base\Exception\UndefinedPropertyException;

/**
 * Трейт поддежки Singleton-экземпляра.
 * Нужен для поддержки связного вызова методов вида: MyClass::setMethod()->setMethod()->method()
 * 
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
trait SingletonInstanceTrait
{
    /**
     * @var self singleton экземпляр
     */
    protected static $_instance;

    /**
     * Получение singleton экземпляра.
     * @todo добавить поддержку аргуметров func_get_args() для передачи в конструктор
     * @return self
     */
    public static function instance()
    {
        if (empty(static::$_instance)) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    /**
     * Сеттер свойства экземпляра.
     * @param string имя свойства
     * @param mixed значение свойства
     * @return self
     */
    public static function instanceSet(string $name, $value = null)
    {
        static::instance()->$name = $value;
        return static::instance();
    }

    /**
     * Удаление свойства экземпляра.
     * @param string имя свойства
     * @return self
     */
    public static function instanceUnset(string $name)
    {
        $instance = static::instance();
        unset($instance->$name);
        return $instance;
    }

    /**
     * Проверка наличия значения свойства экземпляра.
     * @param string имя свойства
     * @return bool
     */
    public static function instanceHas(string $name): bool
    {
        $instance = static::instance();
        return isset($instance->$name) ? true : false;
    }

    /**
     * Геттер значения свойства экземпляра.
     * @param string имя свойства
     * @return mixed значение свойства
     */
    public static function instanceGet(string $name)
    {
        $instance = static::instance();
        if (!isset($instance->$name)) {
            throw new UndefinedPropertyException;
        }
        return $instance->$name;
    }
}
