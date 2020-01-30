<?php
namespace HB9HCR\Entity;

use HB9HCR\Base\Item;

/**
 * Class Map
 *
 * @property string $center
 * @property array $markers
 * @property string $type
 * @property int $zoom
 */
class Map extends Item
{
    const TYPE_ROAD = 'road';
    const TYPE_SATELLITE = 'satellite';
    const TYPE_TERRAIN = 'terrain';

    const TYPES = [
        self::TYPE_ROAD,
        self::TYPE_SATELLITE,
        self::TYPE_TERRAIN
    ];

    /**
     * @return string
     */
    public function hash(): string
    {
        return sprintf(
            '%s-%s-%s-%d',
            $this->center,
            $this->marker,
            $this->type,
            $this->zoom
        );
    }
}