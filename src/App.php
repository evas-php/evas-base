<?php
/**
 * @package evas-php/evas-base
 */
namespace Evas\Base;

use Evas\Base\AppDirTrait;
use Evas\Base\AppLoadTrait;
use Evas\Base\SingletonInstanceTrait;

/**
 * Базовый класс приложения.
 * @author Egor Vasyakin <e.vasyakin@itevas.ru>
 * @since 1.0
 */
class App
{
    /**
     * Подключаем поддержку singleton-экземпляра приложения.
     */
    use SingletonInstanceTrait;

    /**
     * Подключаем поддержку директории приложения.
     */
    use AppDirTrait;

    /**
     * Подключаем поддержку подгрузки содержимого файлов.
     */
    use AppLoadTrait;
}
