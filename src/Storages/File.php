<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 05.06.16
 * Time: 15:00
 */

namespace Language\Storages;


class File implements Storage
{
    /**
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function put($key, $value)
    {
        var_dump($key);
        if (!is_dir(dirname($key))) {
            mkdir(dirname($key), 0755, true);
        }

        return (bool) file_put_contents($key, $value);
    }
}