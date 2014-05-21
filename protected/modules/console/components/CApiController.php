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
    const ERROR_UNAUTHORIZED = 401; // 未登录
    const ERROR_SHOW_MESSAGE = 400; // 显示错误消息
    const ERROR_PARAMS_INVALID = 406; // 数据参数错误
    const ERROR_SYSTEM = 500; // 服务器代码错误

    private $_hasError = false;
    private $_status = 200;
    private $_response = null;
    private $_member = null; // 当前被管理的公众号

    public function member()
    {
        if ($this->_member === null) {
            $this->getModule()->getParentModule()->getModule('member');
            $this->_member = Member::current();
        }

        return $this->_member;
    }

    public function getRequestData()
    {
        $request = Mod::app()->request;

        switch ($request->getRequestType()) {
            case 'GET':
                return $_GET;
                break;
            case 'PUT':
                $json = json_decode($request->getRawBody(), true);
                if ($json === null) {
                    $this->errorMessage('数据格式错误.');

                    return null;
                }

                return $json;
                break;
            case 'DELETE':
                return null;
                break;
            default:
                return $_POST;
        }
    }

    public function run($actionID)
    {
        if ($actionID === '') {
            $actionID = Mod::app()->request->getRequestType();
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
        }

        $this->_response = $data;
    }

    protected function beforeAction($action)
    {
        return true;
    }

    protected function needLogin()
    {
        $this->_hasError = true;
        $this->_status = self::ERROR_UNAUTHORIZED;
    }

    protected function errorMessage($message, $title = '错误')
    {
        $this->_hasError = true;

        $this->_response = array(
            'title'   => $title,
            'message' => $message,
        );

        $this->_status = self::ERROR_SHOW_MESSAGE;
    }

    protected function invalid(Array $errors)
    {
        $this->_response = $errors;
        $this->_status   = self::ERROR_PARAMS_INVALID;
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
        if ($this->_response === null) {
            return '{}';
        }

        $json = json_encode($this->_response);
        if ($json === false) {
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
}