<?php

/**
 * Class ApiController
 */
class ApiController extends CAuthedController
{
    /**
     * 获取当前管理员可操作的公众号列表
     */
    public function actionGet()
    {
        $accountsList = $this->manager->accounts;

        $array = $this->extract($accountsList, Account::extractFields());

        $this->response($array);
    }

    /**
     * 获取公众号数据
     * @param $id
     */
    public function actionGetById($id)
    {
        $account = $this->manager->getAccount($id);

        if ($account) {
            $this->response($account);
        } else {
            $this->errorForbidden();
        }
    }

    /**
     * 更新公众号数据
     */
    public function actionPutById($id)
    {
        $account = $this->manager->getAccount($id);

        if (! $account) {
            $this->errorNotFound();
            return;
        }

        $account->scenario = 'update';
        $account->setAttributes($this->getRequestData());
        if ($account->save()) {
            $this->successNoContent();
        }
        else{
            $this->errorNotAcceptable($account->getErrors());
        }
    }

    /**
     * 切换当前操作的公众号
     */
    public function actionPostById($id)
    {
        $account = $this->manager->getAccount($id);
        if ($account) {
            $this->_auth->account = $account;
        } else {
            $this->errorMessage('没有权限!');
        }
    }
}