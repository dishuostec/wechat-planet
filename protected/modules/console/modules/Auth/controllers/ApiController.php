<?php

/**
 * Class ApiController
 */
class ApiController extends CApiController
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
    public function actionGet()
    {
        if ($this->_auth->isGuest) {
            $this->needLogin();
            return;
        }

        $menu = array(
            'foo' => '测试',
        );
        $this->response($menu);
    }

    /**
     * 获取登录状态
     */
    public function actionPost()
    {
        if ($this->_auth->isGuest) {
//            $this->needLogin();
            $this->errorMessage('请登录');
            return;
        }

        $manager = $this->_auth->manager;

        $this->response($manager);
    }
}