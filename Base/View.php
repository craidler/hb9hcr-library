<?php
namespace HB9HCR\Base;

/**
 * Class View
 */
class View extends Item
{
    /**
     * @var object[]
     */
    public static $plugins = [];

    /**
     * @param array $config
     */
    public static function configure(array $config): void
    {
        static::$plugins = $config['plugins'];
    }

    /**
     * @param string $filename
     * @param array  $data
     * @return string
     */
    public function partial(string $filename, array $data = []): string
    {
        return static::create($data + ['filename' => $filename])->render();
    }

    /**
     * @param string $name
     * @return object
     */
    public function plugin(string $name)
    {
        return static::$plugins[$name]::instance();
    }

    /**
     * @return string
     */
    public function render(): string
    {
        ob_start();
        include $this->filename;
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}
