<?php
namespace HB9HCR\Entity;

use HB9HCR\Base\Collection;

/**
 * Class Roadbook
 */
class Roadbook extends Collection
{
    /**
     * @inheritdoc
     */
    public static function create(?array $data = [], ?string $class = null): Collection
    {
        return parent::create($data, $class ? $class : Waypoint::class);
    }
}