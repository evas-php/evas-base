<?php
/**
 * Халпер для рендера php-конфигов.
 * @package evas-php\evas-base
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Base\Help;

class PhpConfigRender
{
    /** @var int уровень вложенности для пробелов/табов */
    protected $level = 0;
    /** @var bool использовать ли 1 таб вместо 4 пробелов */
    protected $useTabs = false;
    /** @var string|null результат последнего рендера */
    protected $result;

    /**
     * Конструктор.
     * @param mixed|null данные для рендера
     * @param array|null phpdoc
     * @param bool|false использовать ли табы вместо пробелов
     */
    public function __construct($data = null, array $phpDoc = null, bool $useTabs = false)
    {
        $this->useTabs($useTabs);
        if ($data) $this->render($data, $phpDoc);
    }

    public function __toString()
    {
        return $this->result ?? '';
    }

    /**
     * Переключить использование табов.
     * @param bool|null исользовать табы
     * @return self
     */
    public function useTabs(bool $useTabs = true)
    {
        $this->useTabs = $useTabs;
        return $this;
    }

    /**
     * Рендер конфига.
     * @param mixed данные конфига
     * @param array|null строки phpdoc
     * @return string
     */
    public function render($data, array $phpDoc = null): string
    {
        return $this->result = $this->line('<\?php')
        . $this->phpDoc($phpDoc)
        . 'return ' . $this->renderEntity($data);
    }

    /**
     * Рендер phpdoc
     * @param array|null строки phpdoc
     * @return string
     */
    protected function phpDoc(array $phpDoc = null): string
    {
        if (empty($phpDoc)) return '';
        foreach ($phpDoc as &$line) {
            $line = " * {$line}\n";
        }
        return "/**\n" . implode('', $phpDoc) . " */\n";
    }

    /**
     * Рендер переменной или элемента массива.
     * @param mixed данные
     * @param string|numeric|null ключ элемента в массиве
     * @return string|numeric|null
     */
    protected function renderEntity($entity, $key = null)
    {
        if ($entity instanceof \Closure) $entity = null;
        if (is_object($entity)) $entity = get_object_vars($entity);
        if (is_array($entity)) return $this->renderArray($entity, $key);

        if (is_string($entity)) $entity = $this->quoteString($entity);
        else if (!is_numeric($entity)) $entity = 'null';
        if (is_null($key)) {
            $entity .= ';';
        } else {
            $key = $this->arrayKeyWithAssignment($key);
            $entity .= ',';
        }
        return $this->line($key . $entity);
    }

    /**
     * Рендер массива.
     * @param array массив
     * @param string|numeric|null ключ элемента в родительском массиве
     * @return string
     */
    protected function renderArray(array $entity, $key = null): string
    {
        [$start, $end] = is_null($key)
        ? [ '[', '];' ]
        : [ $this->arrayKeyWithAssignment($key).'[', '],' ];

        $out = $this->line($start);
        $this->level++;
        foreach ($entity as $key => $value) {
            $out .= $this->renderEntity($value, $key);
        }
        $this->level--;
        $out .= $this->line($end);
        return $out;
    }

    /**
     * Экранирование строки.
     * @param string строка
     * @return string экранированная строка
     */
    protected function quoteString(string $value): string
    {
        return "'" . addslashes($value) . "'";
    }

    /**
     * Получение экранированного ключа элемента массива с оператом присвоения.
     * @param string|numeric ключ
     * @return string
     */
    protected function arrayKeyWithAssignment($key): string
    {
        if (is_string($key)) $key = $this->quoteString($key);
        return $key . ' => ';
    }

    /**
     * Рендер строки с отступом и переносом.
     * @param string строка
     * @return string готовая строка
     */
    protected function line(string $line): string
    {
        [$offset, $multiplier] = $this->useTabs ? ["\t", 1] : [' ', 4];
        return str_pad('', $this->level * $multiplier, $offset) . $line . "\n";
    }
}
