<?php

class CallbackController extends CMultiWechatController
{
    public function actionMessageText($data)
    {
        if (strcasecmp($data->Content, 'love')) {
            $text = $this->wechat->createMessageText();
            $text->Content = '您发送了：'.$data->Content;
            $this->response($text);
            return;
        }

        $openId = $data->FromUserName;

        /**
         * @var Session $session
         */
        $session = Session::model()->findByAttributes(array(
            'open_id' => $openId,
        ));

        $baseUrl = Mod::app()->params['baseUrl'];

        $choice = array(
            $this->_link('查看用户列表', $baseUrl),
        );

        $prefix = '您还未注册，';

        if (is_null($session)) {
            $choice[] = '上传头像图片，注册交友';
        } else {
            $session->refresh()->save();
            $hash = is_null($session) ? '' : $session->hash;

            /**
             * @var User $user
             */
            $user = User::model()->findByPk($openId);

            if ($user) {
                $prefix = $user->nickname.'，';
                $choice[] = $this->_link('修改个人信息', $baseUrl, $hash, 'update');
            } else {
                $choice[] = $this->_link('填写个人信息', $baseUrl, $hash, 'reg');
            }

            $choice[] = $this->_expire();
        }

        $message = sprintf("%s您可以\n\n%s", $prefix, implode("\n\n", $choice));

        $text = $this->wechat->createMessageText();
        $text->Content = $message;
        $this->response($text);
    }

    public function actionMessageImage($data)
    {
        $openId = $data->FromUserName;

        /**
         * @var Session $session
         */
        $session = Session::model()->findByAttributes(array(
            'open_id' => $openId,
        ));

        if (is_null($session)) {
            $session = new Session;
            $session->open_id = $openId;
        }

        $session->avatar_url = $data->PicUrl;
        $session->media_id = $data->MediaId;

        $session->refresh()->save();

        $choice = array();

        /**
         * @var User $user
         */
        $user = User::model()->findByPk($openId);

        $baseUrl = Mod::app()->params['baseUrl'];

        $prefix = '您还未注册，';

        if ($user) {
            $prefix = $user->nickname.'，';
            $choice[] = $this->_link('更新头像图片', $baseUrl, $session->hash,
                'update');
        } else {
            $choice[] = $this->_link('填写个人信息', $baseUrl, $session->hash,
                'reg');
        }

        $choice[] = $this->_expire();

        $message = sprintf("%s您可以\n\n%s", $prefix,
            implode("\n\n", $choice));

        $text = $this->wechat->createMessageText();
        $text->Content = $message;
        $this->response($text);
    }

    private function _link($text, $host, $hash = NULL, $op = NULL)
    {
        $query = array(
            'hash' => $hash,
        );
        if ($op) {
            $query['op'] = $op;
        }

        return sprintf('<a href="%s/session?%s">%s</a>',
            $host,
            http_build_query($query),
            $text
        );
    }

    private function _expire()
    {
        return '链接到期时间 '.date('H:i:s d/m/Y', time() + Session::$timeout);
    }
}

