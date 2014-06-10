<?php

class CallbackController extends CMultiWechatController
{
    public function actionMessageText(WechatMessageText $data)
    {
        /**
         * @var Account $account
         */
        $account = Account::model()->findByPk($data->ToUserName);

        $list = $account->triggerText(array(
            'condition' => 'keyword=:keyword',
            'params'    => array(
                ':keyword' => $data->Content,
            ),
            'limit'     => 1,
        ));

        if (! count($list)) {
            return;
        }
        /**
         * @var TriggerText $trigger
         */
        $trigger = array_pop($list);

        $response = WeChatResponse::factory($trigger->response, $data);

        $this->response($response);
    }

    public function actionEventClick(WechatEventClick $data)
    {
    }
}

