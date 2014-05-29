<?php

class CallbackController extends CMultiWechatController
{
    public function actionMessageText(WechatMessageText $data)
    {
        /**
         * @var Account $account
         */
        $account = Account::model()->findByPk($data->ToUserName);

        $trigger = $account->triggerText(array(
            'condition' => 'keyword=:keyword',
            'params'    => array(
                ':keyword' => $data->Content,
            ),
        ));

        if (count($trigger)) {
            /**
             * @var TriggerText $trigger
             */
            $trigger = array_pop($trigger);
            $response = $trigger->response;
            if(empty($response))
            {
               $message = '错误:没有对应的返回内容'.$data->Content;
            }
            else{
                $message = $response->content;
            }
        } else {
            $message = '错误：找不到触发器 '.$data->Content;
        }

        $text = $this->wechat->createMessageText();
        $text->Content = $message;
        $this->response($text);
    }

    public function actionMessageImage($data)
    {
    }
}

