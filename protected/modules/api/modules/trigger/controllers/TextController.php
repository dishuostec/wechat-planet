<?php

/**
 * Class TextController
 */
class TextController extends CAuthedController
{
    public function actionGet()
    {
        $list = $this->_auth->account->triggerText;

        $this->response($list);
    }

    /**
     * 创建
     */
    public function actionPost()
    {
        $data = $this->getRequestData();

        $trigger = new TriggerText();

        $trigger->attributes = $data;
        $trigger->account_id = $this->_auth->account->id;

        if ($trigger->save()) {
            $this->response($trigger);
        } else {
            $this->errorNotAcceptable($trigger->getErrors());
        }
    }

    /**
     * 更新
     * @param $id
     */
    public function actionPutById($id)
    {
        $data = $this->getRequestData();

        $accountId = $this->_auth->account->id;
        $trigger = ResponseText::model()->findByAttributes(array(
            'id'         => $id,
            'account_id' => $accountId,
        ));

        if (! $trigger) {
            $this->errorForbidden();
            return;
        }

        $trigger->attributes = $data;

        if ($trigger->save()) {
            $this->successNoContent();
        } else {
            $this->errorNotAcceptable($trigger->getErrors());
        }
    }
}