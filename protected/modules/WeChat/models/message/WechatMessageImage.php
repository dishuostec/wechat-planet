<?php

class WechatMessageImage extends WechatMessage
{
    public $MsgType = 'image';

    protected $_json_format = array(
        'touser'  => 'ToUserName',
        'msgtype' => 'MsgType',
        'image'   => array(
            'media_id' => 'MediaId',
        ),
    );

    protected $_xml_format = array(
        'ToUserName',
        'FromUserName',
        'CreateTime',
        'MsgType',
        'Image',
    );

    public $PicUrl; //无，图片链接
    public $MediaId; //是，图片消息媒体id，可以调用多媒体文件下载接口拉取数据。

    protected $_image = array();

    public function setImage($value)
    {
        $this->_image = $value;
    }

    public function getImage()
    {
        return $this->_image;
    }
}
