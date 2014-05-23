<?php

/**
 * Class ApiController
 *
 *  Resource   | GET           | PUT    | POST               | DELETE
 *  Collection | list          | -      | Create a new entry | Delete collection
 *  Element    | Retrieve data | Update | -                  | Delete element
 */
class CApiController extends CController
{
    const SUCCESS_OK = 200; // 请求成功
    const SUCCESS_CREATED = 201; // 请求成功且创建了一个资源
    const SUCCESS_NO_CONTENT = 204; // 请求成功，但是不会返回任何内容（比如：内容是空的）

    const ERROR_SHOW_MESSAGE = 400; // 请求无效（比如：缺少必要的参数）
    const ERROR_UNAUTHORIZED = 401; // 鉴权失败，用户需要重新验证身份
    const ERROR_FORBIDDEN = 403; // 访问被拒绝（比如：没有访问权限）
    const ERROR_NOT_FOUND = 404; // 资源不存在
    const ERROR_NOT_ACCEPTABLE = 406; // 数据参数错误
    const ERROR_TOO_MANY_REQUEST = 429; // 达到API请求次数限制
    const ERROR_SERVICE_UNAVAILABLE = 503; // 服务器暂时不可用（比如：服务器维护）

    private $_hasError = FALSE;
    private $_status = 200;
    private $_response = NULL;

    public function getRequestData()
    {
        $request = Mod::app()->request;

        switch ($request->getRequestType()) {
            case 'GET':
                return $_GET;
                break;
            case 'PUT':
                $json = json_decode($request->getRawBody(), TRUE);
                if ($json === NULL) {
                    $this->errorMessage('数据格式错误.');

                    return NULL;
                }

                return $json;
                break;
            case 'DELETE':
                return NULL;
                break;
            default:
                return $_POST;
        }
    }

    public function run($actionID)
    {
        $actionID = Mod::app()->request->getRequestType().ucfirst($actionID);

        $id = Mod::app()->request->getQuery('id');
        if (! empty($id)) {
            $actionID .= 'ById';
        }

        parent::run($actionID);
    }

    public function missingAction($actionID)
    {
        $this->errorMessage(Mod::t('mod',
            'The system is unable to find the requested action "{action}".',
            array(
                '{action}' => $actionID
            )));

        $this->sendResponse();
    }

    protected function response($data)
    {
        if ($this->_hasError) {
            return;
        }

        if ($data instanceof CActiveRecord) {
            $data = $data->getAttributes();
        } elseif (is_string($data)) {
            $data = array('data' => $data);
        }

        $this->_response = $data;
    }

    protected function beforeAction($action)
    {
        return TRUE;
    }

    /**
     * 201 请求成功且创建了一个资源
     */
    protected function successCreated()
    {
        $this->_status = self::SUCCESS_CREATED;
    }

    /**
     * 204 请求成功，但是不会返回任何内容（比如：内容是空的）
     */
    protected function successNoContent()
    {
        $this->_status = self::SUCCESS_NO_CONTENT;
    }

    /**
     * 401 鉴权失败，用户需要重新验证身份
     */
    protected function errorUnauthorized()
    {
        $this->_hasError = TRUE;
        $this->_status = self::ERROR_UNAUTHORIZED;
    }

    /**
     * 400 请求无效（比如：缺少必要的参数）
     * @param $message
     * @param null $params
     */
    protected function errorMessage($message, $params = NULL)
    {
        $this->_hasError = TRUE;

        $this->_response = array(
            'message' => $message,
        );

        if (! empty($params)) {
            $this->_response['params'] = $params;
        }

        $this->_status = self::ERROR_SHOW_MESSAGE;
    }

    /**
     * 403 访问被拒绝（比如：没有访问权限）
     */
    protected function errorForbidden()
    {
        $this->_hasError = TRUE;
        $this->_status = self::ERROR_FORBIDDEN;
    }

    /**
     * 404 资源不存在
     */
    protected function errorNotFound()
    {
        $this->_hasError = TRUE;
        $this->_status = self::ERROR_NOT_FOUND;
    }

    /**
     * 406 数据参数错误
     */
    protected function errorNotAcceptable($errors)
    {
        $this->_hasError = TRUE;
        $this->_status = self::ERROR_NOT_ACCEPTABLE;
        $this->_response = $errors;
    }

    protected function invalid(Array $errors)
    {
        $this->_response = $errors;
        $this->_status = self::ERROR_PARAMS_INVALID;
    }

    protected function afterAction($action)
    {
        $this->sendResponse();
    }

    private function sendResponse()
    {
        $status = $this->_status;

        $json = $this->prepareResponseString();

        header('HTTP/1.1 '.$status.' '.$this->getStatusMessage($status));
        header('Content-type: application/json');
        //        Mod::app()->end();

        echo $json;
    }

    public function prepareResponseString()
    {
        if ($this->_response === NULL) {
            return '{}';
        }

        $json = json_encode($this->_response);
        if ($json === FALSE) {
            Mod::log('Invalid json data'.var_export($this->_response),
                CLogger::LEVEL_ERROR);
            $this->_status = self::ERROR_SYSTEM;

            return json_encode(array('messasge' => '数据错误'));
        }

        return $json;
    }

    private function getStatusMessage($status)
    {
        $codes = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',

        );

        return (isset($codes[$status])) ? $codes[$status] : '';
    }

    protected function extract($source, $fields)
    {
        $array = array();
        if (Arr::is_assoc($fields)) {
            $key = array_pop(array_keys($fields));
            $fields = $fields[$key];
        } else {
            $key = NULL;
        }

        $not_array = ! is_array($source);

        if ($not_array) {
            $source = array($source);
        }

        foreach ($source as $item) {
            $data = array();
            foreach ($fields as $field) {
                $data[$field] = $item->$field;
            }
            if ($key) {
                $array[$item->$key] = $data;
            } else {
                $array[] = $data;
            }
        }

        return $not_array ? array_pop($array) : $array;
    }
}