<?php

class CAuthedController extends CApiController
{
    /**
     * @var Auth
     */
    protected $_auth;

    /**
     * @var Manager
     */
    protected $manager;

    public function init()
    {
        parent::init();
        /**
         * @var Auth $auth
         */
        $auth = Mod::app()->Auth;
        $this->_auth = $auth;
        $this->manager = $auth->manager;
    }

    /**
     *
     * @param CFilterChain $filterChain
     */
    public function filterAuthed(CFilterChain $filterChain)
    {
        if (! $this->_auth->isGuest) {
            $filterChain->run();
        }
    }

    public function filters()
    {
        return array(
            'Authed',
        );
    }
}
