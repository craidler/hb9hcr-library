<?php
namespace HB9HCR\Entity;

use HB9HCR\Base\Item;

/**
 * Class Distance
 *
 * @property mixed $km
 * @property int $m
 * @property int $value
 */
class Distance extends Item
{
    /**
     * @param string $rounding
     * @param array $params
     * @return mixed
     */
    public function km(string $rounding = 'ceil', array $params = [])
    {
        return call_user_func_array($rounding, array_merge([$this->value * 0.001], $params));
    }

    /**
     * @return int
     */
    public function m(): int
    {
        return $this->value;
    }
}