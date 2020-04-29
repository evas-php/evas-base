<?php
/**
 * @package evas-php/evas-base
 */
namespace Evas\Base;

use Evas\Base\AppDirTrait;
use Evas\Base\AppLoadTrait;
use Evas\Di\AppDiTrait;

/**
 * Базовый класс приложения.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
class App
{
    /**
     * Подключаем поддержку di-контейнера приложения.
     * Подключаем поддержку директории приложения.
     * Подключаем поддержку подгрузки файла.
     */
    use AppDiTrait;
    use AppDirTrait;
    use AppLoadTrait;

    /**
     * Получение единственного экземпляра приложения.
     * для последовательного вызова методов приложения
     * @return self
     */
    public static function instance(): object
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static;
        }
        return $instance;
    }
}
