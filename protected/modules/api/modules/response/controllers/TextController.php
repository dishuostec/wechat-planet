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
        $response = $this->_auth->account->fetchResponseText($id);

        if (is_null($response)) {
            $this->errorForbidden();
            return;
        }

        $response->attributes = $this->getRequestData();

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
        $response = $this->_auth->account->fetchResponseText($id);

        if (is_null($response)) {
            $this->errorForbidden();
            return;
        }

        $response->delete();
        $this->successNoContent();
    }
}