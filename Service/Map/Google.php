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
     * @param array $params
     * @return Image
     */
    public function image(Map $map, array $params = []): Image
    {
        $params = $params + ['size' => '475x500'];
        $filename = sprintf('%s/map/%s.static.png', $this->cache, $map->hash);

        if (!file_exists($filename)) {
            file_put_contents($filename, file_get_contents(sprintf(
                'https://maps.googleapis.com/maps/api/staticmap?center=%s&zoom=%d&size=%s&maptype=%s&markers=%s&key=%s',
                $map->center,
                $map->zoom,
                $params['size'],
                $map->type,
                implode('&markers=', $map->markers),
                $this->key
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
