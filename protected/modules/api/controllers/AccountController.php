<?php

/**
 * Class AccountController
 */
class AccountController extends CAuthedController
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
    public function actionPostDetailById($id)
    {
        if (! ($account = $this->manager->getAccount($id))) {
            $this->errorForbidden();
            return;
        }

        $array = Arr::merge($account->getAttributes(),
            $this->extract($account, array('url')));

        $this->response($array);
    }

    /**
     * 更新公众号数据
     */
    public function actionPutById($id)
    {
        if (! ($account = $this->manager->getAccount($id))) {
            $this->errorForbidden();
            return;
        }

        $account->scenario = 'update';
        $account->setAttributes($this->getRequestData());

        if ($account->save()) {
            $this->successNoContent();
        } else {
            $this->errorNotAcceptable($account->getErrors());
        }
    }

    /**
     * 切换当前操作的公众号
     */
    public function actionPostCurrentById($id)
    {
        if (! ($account = $this->manager->getAccount($id))) {
            $this->errorForbidden();
            return;
        }

        $this->_auth->account = $account;
    }

    public function actionPostUrlById($id)
    {
        if (! ($account = $this->manager->getAccount($id))) {
            $this->errorForbidden();
            return;
        }

        $account->changeSuffix();
        $this->response($account->url);
    }

    public function actionPostTokenById($id)
    {
        if (! ($account = $this->manager->getAccount($id))) {
            $this->errorForbidden();
            return;
        }

        $account->changeToken();
        $this->response($account->token);
    }
}