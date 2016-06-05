<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 05.06.16
 * Time: 14:27
 */

namespace Language\Generators;

use Language\ApiInterface;
use Language\Storages\File;
use Language\Storages\Storage;

abstract class AbstractGenerator
{

    /**
     * @var ApiInterface
     */
    private $apiInterface;

    /**
     * @var Storage
     */
    private $storageInterface;

    /**
     * AbstractGenerator constructor.
     * @param $apiInterface
     * @param $storageInterface
     */
    public function __construct($apiInterface = null, $storageInterface = null)
    {
        if($apiInterface instanceof ApiInterface) {
            $this->apiInterface = $apiInterface;
        }
        
        if($storageInterface instanceof Storage) {
            $this->storageInterface = $storageInterface;
        }
    }

    /**
     * @return ApiInterface
     */
    public function api()
    {
        if (is_null($this->apiInterface)) {
            $this->apiInterface = new ApiInterface();
        }
        return $this->apiInterface;
    }

    /**
     * @return File
     */
    public function storage()
    {
        if (is_null($this->storageInterface)) {
            $this->storageInterface = new File();
        }
        return $this->storageInterface;
    }
}