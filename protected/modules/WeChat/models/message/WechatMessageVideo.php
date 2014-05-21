<?php

class WechatMessageVideo extends WechatMessage
{
    public $MsgType = 'video';

    protected $_json_format = array(
        'touser'  => 'ToUserName',
        'msgtype' => 'MsgType',
        'video'   => array(
            'title'       => 'Title',
            'description' => 'Description',
            'media_id'    => 'MediaId',
        ),
    );

    protected $_xml_format = array(
        'ToUserName', //是，开发者微信号
        'FromUserName', //是，发送方帐号（一个OpenID）
        'CreateTime', //是，消息创建时间 （整型）
        'MsgType', //是，视频为video
        'Video',
    );

    public $MediaId; //是，视频消息媒体id，可以调用多媒体文件下载接口拉取数据。
    public $ThumbMediaId; //无，视频消息缩略图的媒体id，可以调用多媒体文件下载接口拉取数据。
    public $Title;
    public $Description;

    public function getVideo()
    {
        return array(
            'MediaId'     => $this->MediaId,
            'Title'       => $this->Title,
            'Description' => $this->Description,
        );
    }
}
