<?php
/**
 * Автозагрузчик.
 * Внимание: Автозагрузка по умолчанию запускается относительно родительской 
 * директории для вендоров! Если вам нужно сделать автозагрузку относительно 
 * другой директории, передайте её в конструктор автозагрузчика.
 * @package evas-php\evas-base
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Base;

use Evas\Base\Help\PhpHelp;

include_once __DIR__ .'/Help/PhpHelp.php';
// include_once __DIR__ .'/Help/functions.php';

class Loader
{
    /** @var string базовая директория */
    public $baseDir;
    /** @var array массив директорий */
    public $dirs = [];
    /** @var array маппинг путей пространств имен */
    public $namespaces = [];
    /** @var array маппинг путей файлов по именам */
    public $files = [];

    /**
     * Получение директории запуска.
     * @return string директория запуска
     */
    public static function getRunDir(): string
    {
        static $dir = null;
        if (null === $dir) {
            $filename = $_SERVER['SCRIPT_FILENAME'];
            if (empty($filename)) {
                $filename = 'cli' === PHP_SAPI ? $_SERVER['PHP_SELF']
                    : ($_SERVER['DOCUMENT_ROOT'] . $_SERVER['PHP_SELF']);
            }
            $dir = PhpHelp::pathReal(dirname($filename));
        }
        return $dir;
    }

    /**
     * Получение родиельской директории вендора.
     * @return string директория вендора
     */
    public static function getVendorParentDir(): string
    {
        static $dir = null;
        if (null === $dir) {
            $dir = PhpHelp::pathReal(__DIR__ .'/../../../../');
        }
        return $dir;
    }

    /**
     * Конструктор.
     * @param string|null базовая директория автозагрузки
     */
    public function __construct(string $baseDir = null)
    {
        if (empty($baseDir)) {
            $baseDir = static::getVendorParentDir();
        }
        $this->baseDir($baseDir);
    }

    /**
     * Установка базовой директории.
     * @param string базовая директория
     * @return self
     */
    public function baseDir(string $baseDir): Loader
    {
        $this->baseDir = PhpHelp::pathReal($baseDir);
        return $this;
    }

    /**
     * Добавление пути пространства имен.
     * @param string пространство имен
     * @param string путь
     * @return self
     */
    public function namespace(string $namespace, string $path): Loader
    {
        $namespace = str_replace('\\', '\\\\', $namespace);
        $this->namespaces[$namespace] = PhpHelp::path($path);
        return $this;
    }

    /**
     * Добавление маппинга пространств имен.
     * @param array map
     * @return self
     */
    public function namespaces(array $map): Loader
    {
        foreach ($map as $namespace => $path) {
            $this->namespace($namespace, $path);
        }
        return $this;
    }

    /**
     * Добавление маппинга пространств имен Evas PHP.
     * @throws Exception
     * @return self
     */
    public function useEvas(): Loader
    {
        $evasDir = dirname(dirname(__DIR__));
        $dirs = scandir($evasDir);
        foreach ($dirs as &$dir) {
            if (0 === strpos($dir, 'evas-')) {
                $name = explode('-', $dir);
                foreach ($name as &$sub) {
                    $sub = ucfirst($sub);
                }
                $this->namespace(
                    (implode('\\', $name) . '\\'), 
                    "vendor/evas-php/$dir/src/"
                );
            }
        }
        return $this;
    }

    /**
     * Добавление директории или директорий.
     * @param string директория
     * @return self
     */
    public function dir(string ...$dirs): Loader
    {
        foreach ($dirs as &$dir) {
            $dir = PhpHelp::path($dir);
        }
        $this->dirs = array_merge($this->dirs, $dirs);
        return $this;
    }

    /**
     * Добавление пути к файлу сущности.
     * @param string имя класса/трейта/интерфейса
     * @param string путь файла
     * @return self
     */
    public function file(string $entity, string $path): Loader
    {
        $this->files[$entity] = $path;
        return $this;
    }

    /**
     * Добавление путей к файлам сущностей.
     * @param array маппинг файлов по именам сущностей
     * @return self
     */
    public function files(array $entitiesToFilesMap): Loader
    {
        foreach ($entitiesToFilesMap as $entity => &$path) {
            $this->file($entity, $path);
        }
        return $this;
    }

    /**
     * Запуск автозагрузки.
     * @return self
     */
    public function run(): Loader
    {
        spl_autoload_register([$this, 'autoload']);
        return $this;
    }

    /**
     * Остановка автозагрузки.
     * @return self
     */
    public function stop(): Loader
    {
        spl_autoload_unregister([$this, 'autoload']);
        return $this;
    }

    /**
     * Автозагрузка.
     * @param string имя класса
     */
    public function autoload(string $className)
    {
        foreach ($this->files as $name => $path) {
            if ($className === $name && $this->load($path)) return;
        }
        foreach ($this->namespaces as $name => $path) {
            if (preg_match("/^$name(.*)/", $className, $matches)) {
                if ($this->load($path . $matches[1] . '.php')) return;
            }
        }
        foreach ($this->dirs as &$dir) {
            if ($this->load($dir . $className . '.php')) return;
        }
    }

    /**
     * Загрузка файла.
     * @param string путь к файлу
     * @return bool удалось ли загрузить файл
     */
    public function load(string $filename): bool
    {
        $filename = PhpHelp::path($this->baseDir . $filename);
        // echo $filename . '<br>';
        if (is_readable($filename)) {
            include $filename;
            return true;
        }
        return false;
    }
}
