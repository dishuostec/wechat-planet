<?php

/**
 * Class ApiController
 */
class TextController extends CAuthedController
{
    public function actionGet()
    {
        $list = $this->_auth->account->responseText;

        $list = $this->extract($list, array(
            'id',
            'content',
        ));

        $this->response($list);
    }

    /**
     * 创建
     */
    public function actionPost()
    {
        $data = $this->getRequestData();

        $response = new ResponseText();

        $response->attributes = $data;
        $response->account_id = $this->_auth->account->id;

        if ($response->save()) {
            $this->response($response);
        } else {
            $this->errorNotAcceptable($response->getErrors());
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
        $response = ResponseText::model()->findByAttributes(array(
            'id'         => $id,
            'account_id' => $accountId,
        ));

        if (! $response) {
            $this->errorForbidden();
            return;
        }

        $response->attributes = $data;

        if ($response->save()) {
            $this->successNoContent();
        } else {
            $this->errorNotAcceptable($response->getErrors());
        }
    }

    /**
     * 删除
     * @param $id
     */
    public function actionDeleteById($id)
    {
        $accountId = $this->_auth->account->id;
        $response = ResponseText::model()->findByAttributes(array(
            'id'         => $id,
            'account_id' => $accountId,
        ));

        if (! $response) {
            $this->errorForbidden();
            return;
        }

        if ($response->delete()) {
            $this->successNoContent();
        } else {
            $this->errorNotAcceptable($response->getErrors());
        }
    }
}