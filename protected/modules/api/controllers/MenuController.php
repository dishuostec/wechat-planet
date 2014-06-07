<?php

/**
 * Class MenuController
 */
class MenuController extends CAuthedController
{
    /**
     * 获取菜单
     */
    public function actionGet()
    {
        $data = $this->_auth->account->menu;
        $this->response($data);
    }

    /**
     * 更新菜单
     */
    public function actionPut()
    {
        $this->_auth->account->menu = $this->getRequestData();
        $this->successNoContent();
    }

    /**
     * 发布菜单到微信服务器
     */
    public function actionPostPublish()
    {
        // TODO: 微信实现
    }

    /**
     * 获取所有可用菜单项
     */
    public function actionGetItem()
    {
        $menus = $this->_auth->account->menuItems;
        $array = $this->extract($menus, MenuItem::extractFields());
        $this->response($array);
    }

    /**
     * 创建菜单项
     */
    public function actionPostItem()
    {
        $menuItem = new MenuItem();
        $menuItem->attributes = $this->getRequestData();
        $menuItem->account_id = $this->_auth->account->id;

        if ($menuItem->save()) {
            $this->response($menuItem);
        } else {
            $this->errorNotAcceptable($menuItem->getErrors());
        }
    }

    /**
     * 更新菜单项数据
     * @param $id
     */
    public function actionPutItemById($id)
    {
        $menuItem = $this->_auth->account->fetchMenuItem($id);

        $menuItem->attributes = $this->getRequestData();

        if (is_null($menuItem)) {
            $this->errorForbidden();
            return;
        }

        if ($menuItem->save()) {
            $this->successNoContent();
        } else {
            $this->errorNotAcceptable($menuItem->getErrors());
        }
    }

    /**
     * 删除菜单项
     * @param $id
     */
    public function actionDeleteItemById($id)
    {
        $menuItem = $this->_auth->account->fetchMenuItem($id);

        if (is_null($menuItem)) {
            $this->errorForbidden();
            return;
        }

        $menuItem->delete();
        $this->successNoContent();
    }
}