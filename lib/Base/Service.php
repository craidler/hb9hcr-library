<?php
namespace HB9HCR\Base;

/**
 * Class Service
 */
class Service extends Item
{
    /**
     * @var
     */
    private static $instance;

    /**
     * @param array $data
     */
    public static function configure(array $data = []): void
    {
        static::instance()->exchangeArray($data);
    }

    /**
     * @return static
     */
    public static function instance(): self
    {
        return static::$instance ?? static::$instance = new static;
    }
}