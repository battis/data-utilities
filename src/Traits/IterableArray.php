<?php

namespace Battis\DataUtilities\Traits;

/**
 * @template K
 * @template V
 */
trait IterableArray
{
    /** @var array<K, V> */
    private ?array $iterable_array = null;

    /** @var K[] */
    private ?array $iterable_array_keys = null;

    protected function isIterableAs(&$array): void
    {
        $this->iterable_array = &$array;
    }

    /**
     * @return V
     */
    public function current(): mixed
    {
        return $this->iterable_array[$this->key()];
    }

    /**
     * @return K
     */
    public function key(): mixed
    {
        return current($this->iterable_array_keys);
    }

    public function next(): void
    {
        next($this->iterable_array_keys);
    }

    public function rewind(): void
    {
        $this->iterable_array_keys = array_keys($this->iterable_array);
        reset($this->iterable_array_keys);
    }

    public function valid(): bool
    {
        return $this->offsetExists($this->key());
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->iterable_array);
    }

    /**
     * @param K $offset
     * @return V
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->iterable_array[$offset];
    }

    /**
     * @param K $offset
     * @param V $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->iterable_array[$offset] = $value;
    }

    /**
     * @param K $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->iterable_array[$offset]);
    }

    public function count(): int
    {
        return count($this->iterable_array);
    }
}
