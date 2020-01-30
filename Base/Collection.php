<?php
namespace HB9HCR\Base;

require_once 'Item.php';

use ArrayObject, Closure;

/**
 * Class Collection
 */
class Collection extends ArrayObject
{
    const MOVE_UP = 0;
    const MOVE_DOWN = 1;

    /**
     * @var string
     */
    protected $class = Item::class;

    /**
     * @var string|null
     */
    protected $filename;

    /**
     * @param array|null  $data
     * @param string|null $class
     * @return static
     */
    public static function create(?array $data = [], ?string $class = null): self
    {
        $instance = new static;
        $instance->class = $class ? $class : Item::class;
        foreach ($data as $item) $instance->append($item);
        return $instance;
    }

    /**
     * @param string      $filename
     * @param string|null $class
     * @return static
     */
    public static function load(string $filename, ?string $class = null): self
    {
        if (file_exists($filename)) {
            $data = json_decode(file_get_contents($filename), JSON_OBJECT_AS_ARRAY);
            $instance = static::create($data['collection'], $data['class']);
        }
        else {
            $instance = static::create([], $class);
        }

        $instance->filename = $filename;
        return $instance;
    }

    /**
     * @inheritdoc
     * @return static
     */
    public function append($value): self
    {
        parent::append($value instanceof $this->class ? $value : call_user_func_array([$this->class, 'create'], [$value]));
        return $this;
    }

    /**
     * @param Item|string|int $needle
     * @return $this
     */
    public function delete($needle): self
    {
        $index = $this->index($needle);
        if ($index) $this->offsetUnset($index);
        return $this;
    }

    /**
     * @param Item|string|int $needle
     * @return Item|null
     */
    public function item($needle)
    {
        $id = $needle instanceof Item ? $needle->id : $needle;
        foreach ($this as $i => $item) if ($i === $id || $item->id === $id) return $item;
        return null;
    }

    /**
     * @param Closure $closure
     * @return Collection
     */
    public function filter(Closure $closure): Collection
    {
        $collection = call_user_func_array([get_called_class(), 'create'], [[], $this->class]);
        foreach ($this as $item) if ($closure($item)) $collection->append($item);
        return $collection;
    }

    /**
     * @param Item|string|int $needle
     * @param int $direction
     * @return $this
     */
    public function move($needle, int $direction): self
    {
        $index = $this->index($needle);
        if (null === $index) return $this;
        $ai = $index;
        $bi = self::MOVE_UP === $direction ? $ai - 1 : $ai + 1;
        $a = $this->offsetExists($ai) ? $this->offsetGet($ai) : null;
        $b = $this->offsetExists($bi) ? $this->offsetGet($bi) : null;
        if (null === $a || null === $b) return $this;
        $this->offsetSet($ai, $b);
        $this->offsetSet($bi, $a);
        return $this;
    }

    /**
     * @param Item|string|int $needle
     * @return int|null
     */
    public function index($needle)
    {
        if (is_int($needle) && $this->offsetExists($needle)) return $needle;
        $id = $needle instanceof Item ? $needle->id : $needle;
        foreach ($this as $i => $item) if ($item->id === $id) return $i;
        return null;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function update(array $data): self
    {
        if (array_key_exists('id', $data)) {
            $item = $this->item($data['id']);

            if ($item) {
                $item->update($data);
                return $this;
            }
        }

        return $this->append(call_user_func_array([$this->class, 'create'], [$data]));
    }

    /**
     * @param string|null $filename
     * @return $this
     */
    public function persist(?string $filename = null): self
    {
        $filename = $filename ?? $this->filename;
        if (!$filename) return $this;
        file_put_contents($filename, json_encode([
            'class' => $this->class,
            'collection' => $this->toArray(),
        ]));
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = [];
        foreach ($this as $item) $data[] = $item->toArray();
        return $data;
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}
