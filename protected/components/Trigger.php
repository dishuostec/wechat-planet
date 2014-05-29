<?php

/**
 * Class Trigger
 * @property number $id
 * @property string $account_id
 * @property number $response_type
 * @property number $response_id
 * @property Response $response
 */
class Trigger extends ActiveRecord
{
    protected $_response;

    /**
     * @param string $attribute the name of the attribute to be validated
     * @throws ErrorException
     */
    public function responseExist($attribute)
    {
        if (! Response::exist($this->response_type, $this->response_id,
            $this->account_id)
        ) {
            $this->addError($attribute, '指定的响应不存在');
        }
    }

    public function getResponse()
    {
        if (! $this->_response) {
            $this->_response = Response::get($this->response_type,
                $this->response_id, $this->account_id);
        }
        return $this->_response;
    }
}