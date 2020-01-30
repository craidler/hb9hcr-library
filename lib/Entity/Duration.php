<?php
namespace HB9HCR\Entity;

use HB9HCR\Base\Item;

/**
 * Class Duration
 *
 * @property int $h
 * @property int $m
 * @property int $s
 * @property int $value
 */
class Duration extends Item
{
    /**
     * @param bool $round
     * @return int
     */
    public function h(bool $round = false): int
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function s(): int
    {
        return $this->value;
    }
}