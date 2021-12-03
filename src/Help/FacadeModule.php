<?php
/**
 * Халпер фасадных модулей.
 * @package evas-php\evas-base
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Base\Help;

use Evas\Base\App;
use Evas\Base\Help\Facade;

class FacadeModule extends Facade
{
    /** @static string имя модуля в DI */
    const MODULE_NAME = null;
    /** @static string путь к конфигу по умолчанию, если он есть */
    const DEFAULT_CONFIG_PATH = null;

    /** @var array конфиг */
    protected $config = [];

    /**
     * Монтирование экзмепляра модуля в фасад из DI.
     * @throws \InvalidArgumentException
     */
    protected static function mountDefault()
    {
        if (!static::MODULE_NAME) {
            throw new \InvalidArgumentException(sprintf(
                'Name for module %s must be exists', __CLASS__
            ));
        }
        if (App::di()->has(static::MODULE_NAME)) {
            $module = App::di()->get(static::MODULE_NAME);
        } else {
            $module = new static;
        }
        static::mount($module);
    }

    /**
     * Конструктор.
     * @param array|null конфиг или путь к конфигу
     */
    public function __construct($config = null)
    {
        if (static::DEFAULT_CONFIG_PATH) $this->setConfig(static::DEFAULT_CONFIG_PATH);
        if ($config) $this->setConfig($config);
    }

    /**
     * Установка конфига модуля.
     * @param array|string конфиг или путь к конфигу
     * @return self
     * @throws \InvalidArgumentException
     */
    protected function setConfig($config)
    {
        if (is_string($config)) {
            $config = App::include($filename = $config);
            if (!is_array($config)) {
                throw new \InvalidArgumentException(sprintf(
                    'Module %s config "%s" must return array, %s given',
                    static::MODULE_NAME, $filename, gettype($config)
                ));
            }
        } else if (!is_array($config)) {
            throw new \InvalidArgumentException(sprintf(
                'Argument 1 passed by %s() must be string or array, %s given',
                __METHOD__, gettype($config)
            ));
        }
        $this->config = array_merge_recursive($this->config(), $config);
        return $this;
    }

    /**
     * Получение конфига модуля.
     * @return array конфиг
     */
    protected function config(): array
    {
        return $this->config;
    }
}
