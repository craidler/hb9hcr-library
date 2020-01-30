<?php
namespace HB9HCR\Service\Map;

use HB9HCR\Base\Service;
use HB9HCR\Entity\Image;
use HB9HCR\Entity\Map;
use HB9HCR\Entity\Route;
use HB9HCR\Entity\Waypoint;

/**
 * Class Google
 */
class Google extends Service
{
    /**
     * @param Map $map
     * @return Image
     */
    public function image(Map $map): Image
    {
        $filename = sprintf('%s/map/%s.static.png', $this->cache, md5($map->center . implode('', $map->markers) . $map->type . $map->zoom));

        if (!file_exists($filename)) {
            file_put_contents($filename, file_get_contents(sprintf(
                'https://maps.googleapis.com/maps/api/staticmap?center=%s&zoom=%d&size=%s&maptype=%s&key=%s&markers=%s',
                $map->center,
                $map->zoom,
                '475x500',
                $map->type,
                $this->key,
                implode('/', $map->markers)
            )));
        }

        return Image::create([
            'filename' => $filename,
            'src' => basename($filename),
            'alt' => $map->center . ' ' . $map->type . ' ' . $map->zoom
        ]);
    }

    /**
     * @param Waypoint $origin
     * @param Waypoint $destination
     * @return Route
     */
    public function route(Waypoint $origin, Waypoint $destination): Route
    {
        $filename = sprintf('%s/route/%s.route.json', $this->cache, md5($origin->position . $destination->position));

        if (!file_exists($filename)) {
            file_put_contents($filename, file_get_contents(sprintf(
                'https://maps.googleapis.com/maps/api/directions/json?origin=%s&destination=%s&key=%s',
                $origin->position,
                $destination->position,
                $this->key
            )));
        }

        return Route::create(json_decode(file_get_contents($filename), JSON_OBJECT_AS_ARRAY));
    }
}