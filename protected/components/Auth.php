<?php

/**
 * Class Auth
 *
 * @property bool $isGuest
 * @property Manager $manager
 * @property Account $account
 */
class Auth extends CApplicationComponent
{
    const KEY_AUTH_TYPE = 'auth';
    const KEY_CURRENT_ACCOUNT_ID = 'aid';
    const AUTH_TYPE_QQ = 1;

    /**
     * @var FakePtUser
     */
    private $_manager;

    /**
     * @var Account
     */
    private $_account;

    private $_accountId;

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

    /**
     * @param Account $account
     */
    public function setAccount(Account $account)
    {
        $this->_accountId = $account->id;
        $this->_cookie->set(self::KEY_CURRENT_ACCOUNT_ID, $this->_accountId);
        $this->_account = $account;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        if ($this->getIsGuest()) {
            return NULL;
        }

        if (is_null($this->_accountId)) {
            $accountId = $this->_cookie->get(self::KEY_CURRENT_ACCOUNT_ID);

            $account = $this->manager->getAccount($accountId);

            if (is_null($account)) {
                $account = $this->manager->accounts(array(
                    'limit' => 1,
                ));
                $account = array_pop($account);
            }

            if (is_null($account)) {
                $this->_accountId = FALSE;
            } else {
                $this->account = $account;
            }
        }

        return $this->_account;
    }

    public function isCurrentAccount($account)
    {
        if (is_null($this->_accountId)) {
            $this->getAccount();
        }
        return $this->_accountId === $account->id;
    }
}

