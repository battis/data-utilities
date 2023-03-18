<?php

namespace Battis\DataUtilities\Traits;

/**
 * @template TKey
 * @template TInternal
 * @template TExternal
 */
trait IterableArray
{
    /** @var array<TKey, TInternal> */
    private ?array $iterable_array = null;

    /** @var TKey[] */
    private ?array $iterable_array_keys = null;

    protected function isIterableAs(array &$array): void
    {
        $this->iterable_array = &$array;
    }

    /**
     * @param TKey $offset
     * @param TExternal $value
     * @return TInternal
     */
    protected function hookSetValue(mixed $offset, mixed $value): mixed
    {
        return $value;
    }

    /**
     * @param TKey $offset
     * @param TInternal $value
     * @return TExternal
     */
    protected function hookGetValue(mixed $offset, mixed $value): mixed
    {
        return $value;
    }

    /**
     * @return TExternal
     */
    public function current(): mixed
    {
        return $this->hookGetValue(
            $this->key(),
            $this->iterable_array[$this->key()]
        );
    }

    /**
     * @return TKey
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
     * @param TKey $offset
     * @return TExternal
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->hookGetValue($offset, $this->iterable_array[$offset]);
    }

    /**
     * @param TKey $offset
     * @param TExternal $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->iterable_array[$offset] = $this->hookSetValue($offset, $value);
    }

    /**
     * @param TKey $offset
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
