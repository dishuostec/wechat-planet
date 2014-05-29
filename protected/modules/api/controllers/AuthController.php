<?php

/**
 * Class AuthController
 */
class AuthController extends CApiController
{
    /**
     * @var Auth
     */
    private $_auth;

    public function init()
    {
        parent::init();
        $this->_auth = Mod::app()->Auth;
    }

    /**
     * 根据身份返回导航菜单
     */
    public function actionGetMenu()
    {
        if ($this->_auth->isGuest) {
            $this->errorUnauthorized();
            return;
        }

        $menu = array(
            array(
                'link'=>'trigger',
                'caption'=>'自动回复',
            ),
            array(
                'link'=>'response',
                'caption'=>'内容管理',
            ),
        );
        $this->response($menu);
    }

    /**
     * 获取登录状态
     */
    public function actionPost()
    {
        if ($this->_auth->isGuest) {
            $this->errorUnauthorized();
            //            $this->errorMessage('请登录');
            return;
        }

        $manager = $this->_auth->manager->getAttributes();

        $manager['accounts'] = $this->extract($this->_auth->manager->accounts,
            Account::extractFields());

        $this->response($manager);
    }
}