<?php

class WechatMessageMusic extends WechatMessage
{
    public $MsgType = 'music';

    protected $_json_format = array(
        'touser'  => 'ToUserName',
        'msgtype' => 'MsgType',
        'music'   => array(
            'title'          => 'Title',
            'description'    => 'Description',
            'musicurl'       => 'MusicUrl',
            'hqmusicurl'     => 'HQMusicUrl',
            'thumb_media_id' => 'ThumbMediaId',
        ),
    );

    protected $_xml_format = array(
        'ToUserName',
        'FromUserName',
        'CreateTime',
        'MsgType',
        'Music',
    );

    public $Title; //否，音乐标题
    public $Description; //否，音乐描述
    public $MusicUrl; //否，音乐链接
    public $HQMusicUrl; //否，高质量音乐链接，WIFI环境优先使用该链接播放音乐
    public $ThumbMediaId; //是，缩略图的媒体id，通过上传多媒体文件，得到的id

    public function getMusic()
    {
        return array(
            'Title'        => $this->Title,
            'Description'  => $this->Description,
            'MusicUrl'     => $this->MusicUrl,
            'HQMusicUrl'   => $this->HQMusicUrl,
            'ThumbMediaId' => $this->ThumbMediaId,
        );
    }
}
