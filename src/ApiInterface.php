<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 05.06.16
 * Time: 14:30
 */

namespace Language;


use Language\Exceptions\ApiCall\NoResponse;
use Language\Exceptions\ApiCall\WrongContent;
use Language\Exceptions\ApiCall\WrongResponse;

class ApiInterface
{

    /**
     * @return mixed
     * @throws NoResponse
     * @throws WrongContent
     * @throws WrongResponse
     */
    public function call()
    {
        $result = forward_static_call_array(array('Language\ApiCall', 'call'), func_get_args());

        // Error during the api call.
        if ($result === false || !isset($result['status'])) {
            throw new NoResponse('Error during the api call');
        }
        // Wrong response.
        if ($result['status'] != 'OK') {
            $e = new WrongResponse();
            $e->setType(!empty($result['error_type']) ? $result['error_type'] : null);
            $e->setCode(!empty($result['error_code']) ? $result['error_code'] : null);
            $e->setMessage((string)$result['data']);
            throw $e;
        }
        // Wrong content.
        if ($result['data'] === false) {
            throw new WrongContent();
        }

        return $result;
    }
}