<?php

/**
 * Class CMultiWechatController
 * 多公众号用
 */
class CMultiWechatController extends CWechatController
{

    public function init()
    {
        $suffix = Arr::get($_GET, 'suffix');
        if (empty($suffix)) {
            return;
        }

        /**
         * @var Account $account
         */
        $account = Account::model()->findByAttributes(array(
            'suffix' => $suffix,
        ));

        if (empty($account)) {
            return;
        }

        $this->wechat = $this->module->factory($account->token, $account->appid,
            $account->appsecret);
    }
}
