<?php

/**
 * Class TextController
 */
class TextController extends CAuthedController
{
    public function actionGet()
    {
        $list = $this->_auth->account->triggerText;

        $list = $this->extract($list, array(
            'id',
            'keyword',
            'response_type',
            'response_id',
        ));

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
        $trigger = $this->_auth->account->fetchTriggerText($id);

        if (is_null($trigger)) {
            $this->errorForbidden();
            return;
        }

        $trigger->attributes = $this->getRequestData();

        if ($trigger->save()) {
            $this->successNoContent();
        } else {
            $this->errorNotAcceptable($trigger->getErrors());
        }
    }

    /**
     * 删除
     * @param $id
     */
    public function actionDeleteById($id)
    {
        $trigger = $this->_auth->account->fetchTriggerText($id);

        if (is_null($trigger)) {
            $this->errorForbidden();
            return;
        }

        $trigger->delete();
        $this->successNoContent();
    }
}