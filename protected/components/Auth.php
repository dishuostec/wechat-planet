<?php

/**
 * Class Auth
 *
 * @property bool $isGuest
 * @property Manager $manager
 */
class Auth extends CApplicationComponent
{
    const KEY_AUTH_TYPE = 'auth';
    const AUTH_TYPE_QQ = 1;

    /**
     * @var FakePtUser
     */
    private $_manager = NULL;
    /**
     * @var Cookie
     */
    private $_cookie;

    public function init()
    {
        parent::init();

        $this->_cookie = Mod::app()->Cookie;

        switch ($this->_cookie->get(self::KEY_AUTH_TYPE)) {
            default:
                // QQ
                $this->authQQ();
        }
    }

    public function authQQ()
    {
        /**
         * @var CPtUser $user
         */
        $user = Mod::app()->user;

        if ($user->isGuest) {
            return FALSE;
        }

        /**
         * @var AuthQQ $authQQ
         */
        $authQQ = AuthQQ::model()->with('manager')
            ->findByPk($user->id);

        if (is_null($authQQ)) {
            return FALSE;
        }

        $this->_manager = $authQQ->manager;

        return TRUE;
    }

    public function getManager()
    {
        return $this->_manager;
    }

    /**
     * @return bool
     */
    public function getIsGuest()
    {
        return is_null($this->_manager);
    }
}

