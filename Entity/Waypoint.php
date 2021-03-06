<?php
namespace HB9HCR\Entity;

use HB9HCR\Base\Item;

/**
 * Class Waypoint
 *
 * @property string $region
 * @property string $name
 * @property string $date
 * @property string $position
 * @property string $latitude
 * @property string $longitude
 * @property string $type
 * @property string $comment
 * @property Map[] $maps
 */
class Waypoint extends Item
{
    const TYPE_ORIGIN = 'origin';
    const TYPE_CAMPING = 'camping';
    const TYPE_BIVOUAC = 'bivouac';
    const TYPE_SERVICE = 'service';
    const TYPE_BREAK = 'break';
    const TYPE_CHECKPOINT = 'checkpoint';
    const TYPE_SIGHTSEEING = 'sightseeing';

    const TYPES = [
        self::TYPE_ORIGIN,
        self::TYPE_CAMPING,
        self::TYPE_BIVOUAC,
        self::TYPE_SERVICE,
        self::TYPE_BREAK,
        self::TYPE_CHECKPOINT,
        self::TYPE_SIGHTSEEING,
    ];

    /**
     * @inheritdoc
     */
    public static function create(?array $data = []): Item
    {
        if (!array_key_exists('position', $data)) $data['position'] = '0,0';
        $data['position'] = str_replace([' ', '!4d'], ['', ','], $data['position']);

        if (!array_key_exists('maps', $data)) {
            $data['maps'] = [];

            foreach ([[Map::TYPE_ROAD, 10], [Map::TYPE_ROAD, 14], [Map::TYPE_SATELLITE, 18]] as $map) {
                $data['maps'][] = [
                    'type' => $map[0],
                    'zoom' => $map[1]
                ];
            }
        }

        foreach ($data['maps'] as $i => $map) {
            if (!array_key_exists('center', $map)) $data['maps'][$i]['center'] = $data['position'];
            if (!array_key_exists('markers', $map)) $data['maps'][$i]['markers'] = [$data['position']];
        }

        $item = parent::create($data);
        foreach ($data['maps'] as $map) $maps[] = Map::create($map);
        $item->offsetSet('maps', $maps);

        return $item;
    }

    /**
     * @inheritdoc
     */
    protected static function filter(array $data): array
    {
        return array_filter($data, function ($k) {
            return in_array($k, ['id', 'date', 'region', 'name', 'position', 'type', 'comment', 'maps']);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @return string
     */
    public function latitude(): string
    {
        return explode(',', $this->position)[0];
    }

    /**
     * @return string
     */
    public function longitude(): string
    {
        return explode(',', $this->position)[1];
    }

    /**
     * @param int $i
     * @return Map
     */
    public function map(int $i = 0): Map
    {
        $maps = $this->maps();
        return array_key_exists($i, $maps) ? $maps[$i] : $maps[count($maps) - 1];
    }

    /**
     * @return Map[]
     */
    public function maps(): array
    {
        return $this->offsetGet('maps');
    }

    /**
     * @param array $data
     * @return Item
     */
    public function update(array $data): Item
    {
        parent::update($data);
        foreach ($this->maps as $map) $map->center = $map->markers[0] = $this->position;
        return $this;
    }
}