<?php
namespace HB9HCR\Entity;

use HB9HCR\Base\Item;

/**
 * Class Duration
 *
 * @property int $h
 * @property int $m
 * @property int $s
 * @property string $hm
 * @property int $value
 */
class Duration extends Item
{
    /**
     * @return string
     */
    public function hm(): string
    {
        return sprintf('%02d:%02d', floor($this->value / 3600), floor($this->value % 3600 / 60));
    }

    /**
     * @param string $round
     * @return int
     */
    public function h(string $round = 'floor')
    {
        return call_user_func_array($round, [$this->value / 3600]);
    }

    /**
     * @param string $round
     * @return int
     */
    public function m(string $round = 'floor'): int
    {
        return call_user_func_array($round, [$this->value / 60]);
    }

    /**
     * @return int
     */
    public function s(): int
    {
        return $this->value;
    }
}