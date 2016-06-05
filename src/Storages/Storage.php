<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 05.06.16
 * Time: 14:59
 */

namespace Language\Storages;


interface Storage
{

    public function get($key);
    
    public function put($key, $value);
}