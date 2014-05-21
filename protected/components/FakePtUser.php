<?php
/**
 * Class FakePtUser
 * 非海豹环境模拟CPtUser类
 *
 * 使用方法：
 *    if(!Mod::app()->user->isGuest){
 *        username = Mod::app()->user->name;
 *        uin = Mod::app()->user->uin;
 *        }
 * 配置说明：
 *  'user'=>array(
 *        'class'=>'system.components.user.CPtUser',
 *        'appid'=>'申请的appid',
 *        'daid' =>'隔离登录态使用的域名id',
 *        'login_type'=>'登录类型, 默认隔离普通登录',
 *        'login_url'=>'跳转地址',
 *        'loginRequiredAjaxResponse'=>'ajax未登录返回'
 *    )
 * @property $name the nick name of the QQ user
 * @property $id the alias of uin
 * @property $mail
 * @property $gender
 * @property $face
 * @property $logintime
 * @property $lastaccess
 */

Mod::import('system.components.user.CPtUser');

class FakePtUser extends CPtUser
{
    private $loginObject;
    private $uin;

    public function getIsGuest()
    {
        return ! $this->_check_login();
    }

    private function _check_login()
    {
        $result = $this->_get_login_object();

        if (is_array($result)) {
            $this->_compat_result($result);
            $this->loginObject = $result;
            return TRUE;
        }
        return FALSE;
    }

    private function _get_login_object()
    {
        $uin = NULL;
        if (isset($_COOKIE['uin'])) {
            $uin = ltrim($_COOKIE['uin'], 'o0');
        } else {
            if (isset($_COOKIE['p_uin'])) {
                $uin = ltrim($_COOKIE['p_uin'], 'o0');
            }
        }

        if (is_null($uin)) {
            return NULL;
        }

        $this->uin = $uin;

        return array(
            'uin'        => $uin,
            'NickName'   => '模拟用户',
            'Email'      => 'user@fake.com',
            'gender'     => NULL,
            'face'       => NULL,
            'logintime'  => NULL,
            'lastaccess' => NULL,
        );
    }

    private function _compat_result(&$arr)
    {
        foreach (array_keys($arr) as $key) {
            $val = $arr[$key];
            unset($arr[$key]);
            switch ($key) {
                case 'NickName':
                    $arr['nick'] = $val;
                    break;
                case 'Email':
                    $arr['mail'] = $val;
                    break;
                default:
                    $arr[strtolower($key)] = $val;
            }
        }
    }

    public function getName()
    {
        if (! empty($this->loginObject)) {
            return $this->loginObject['nick'];
        } else {
            return $this->guestName;
        }
    }

    public function setName($value)
    { /*do nothing*/
    }

    public function getId()
    {
        return $this->uin;
    }

    public function setId($value)
    { /*do nothing*/
    }

    public function getMail()
    {
        return $this->loginObject['mail'];
    }

    public function getUin()
    {
        return $this->uin;
    }

    public function getGender()
    {
        return $this->loginObject['gender'];
    }

    public function getFace()
    {
        return $this->loginObject['face'];
    }

    public function getLogintime()
    {
        return $this->loginObject['logintime'];
    }

    public function getLastaccess()
    {
        return $this->loginObject['lastaccess'];
    }
}