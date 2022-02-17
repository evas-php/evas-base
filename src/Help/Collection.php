<?php
/**
 * Простая коллекция.
 * @package evas-php\evas-base
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Base\Help;

use \ArrayAccess;
use \JsonSerializable;
use \Iterator;
use \IteratorAggregate;

class Collection implements ArrayAccess, JsonSerializable, IteratorAggregate
{
    protected $key = 0;
    protected $items = [];

    /**
     * Приведение данных в массив элементов для коллекции.
     * @param mixed данные для коллекции
     * @return array<TKey, TValue>
     */
    protected function getArrayableItems($items = [])
    {
        if (is_array($items)) {
            return $items;
        } elseif ($items instanceof self) {
            return $items->all();
        } elseif ($items instanceof JsonSerializable) {
            return (array) $items->jsonSerialize();
        } elseif ($items instanceof Traversable) {
            return iterator_to_array($items);
        }
        return (array) $items;
    }

    /**
     * Конструктор.
     * @param array|Collection|null элементы для коллекции
     */
    public function __construct($items = null)
    {
        $items = $this->getArrayableItems($items);
        if ($items) $this->push(...$items);
    }

    // Поддержка доступа как к массиву.

    /**
     * Добавление элемента.
     * @param int сдвиг
     * @param mixed элемент
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * Проверка наличия элемента.
     * @param int сдвиг
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * Удаление элемента.
     * @param int сдвиг
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * Получение элемента.
     * @param int сдвиг
     */
    public function offsetGet($offset)
    {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }

    // Поддержка итератора

    /**
     * Получение элемента текущей итерации.
     * @return mixed данные
     */
    public function current()
    {
        return $this->items[$this->key];
    }

    /**
     * Получение позиции итератора.
     * @return int
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Смещение позиции итертора вперёд.
     */
    public function next()
    {
        ++$this->key;
    }

    /**
     * Сброс позиции итератора.
     */
    public function rewind()
    {
        $this->key = 0;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->key]);
    }

    // Конвертация

    /**
     * Конвертация в массив.
     * @return array
     */
    public function toArray(): array
    {
        return array_merge($this->items);
    }
    
    /*
     * Конвертация в JSON сериализацию.
     * @return array
    */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Конвертация в JSON.
     * @param int|null опции для json_encode
     * @return string
     */
    public function toJson(int $options = JSON_UNESCAPED_UNICODE): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Конвертация в строку.
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * Получение итератора коллекции.
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->toArray());
    }

    /**
     * Получение количества элементов коллекции.
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Получение всех элементов коллекции.
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Добавление элемента в коллекцию.
     * @param mixed элемент
     * @return self
     */
    public function add($item)
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * Добавление элемента/элементов в коллекцию.
     * @param mixed ...$items элементы
     * @return self
     */
    public function push(...$items)
    {
        foreach ($items as &$item) {
            $this->add($item);
        }
        return $this;
    }

    /**
     * Slice элементов коллекции.
     * @param  int|null сдвиг
     * @param  int|null длина
     * @return static
     */
    public function slice(int $offset = 0, int $length = null)
    {
        return new static(array_slice($this->items, $offset, $length, true));
    }
}
