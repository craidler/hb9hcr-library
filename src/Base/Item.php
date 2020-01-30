<?php
namespace HB9HCR\Base;

use ArrayObject;

/**
 * Class Item
 *
 * @property string $id
 */
class Item extends ArrayObject
{
    /**
     * @param array|null $data
     * @return static
     */
    public static function create(?array $data = []): self
    {
        return new static(static::filter($data) + static::defaults());
    }

    /**
     * @return array
     */
    protected static function defaults(): array
    {
        return [
            'id' => uniqid(),
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    protected static function filter(array $data): array
    {
        return $data;
    }

    /**
     * @param string $index
     * @return mixed|null
     */
    public function __get(string $index)
    {
        if (method_exists($this, $index)) return $this->{$index}();
        return $this->offsetExists($index) ? $this->offsetGet($index) : null;
    }

    /**
     * @param string $index
     * @param mixed $value
     */
    public function __set(string $index, $value)
    {
        $this->offsetSet($index, $value);
    }

    /**
     * @param array $data
     * @return $this
     */
    public function update(array $data): self
    {
        foreach (static::filter($data) as $k => $v) $this->offsetSet($k, $v);
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->getArrayCopy();
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}
