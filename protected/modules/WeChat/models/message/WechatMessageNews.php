<?php

class WechatMessageNews extends WechatMessage
{
    public $MsgType = 'news';

    protected $_json_format = array(
        'touser'  => 'ToUserName',
        'msgtype' => 'MsgType',
        'news'    => array(
            'articles' => array(
                array(
                    'title'       => 'Title',
                    'description' => 'Description',
                    'url'         => 'Url',
                    'picurl'      => 'PicUrl',
                ),
            ),
        ),
    );

    protected $_xml_format = array(
        'ToUserName',
        'FromUserName',
        'CreateTime',
        'MsgType',
        'ArticleCount',
        'Articles',
    );

    public $Title; //否，图文消息标题
    public $Description; //否，图文消息描述
    public $PicUrl; //否，图片链接，支持JPG、PNG格式，较好的效果为大图360*200，小图200*200
    public $Url; //否，点击图文消息跳转链接

    protected $_articles = array();

    public function getArticleCount()
    {
        return count($this->_articles) + 1;
    }

    public function getNews()
    {
        return NULL;
    }

    public function getArticles()
    {
        return array_merge(array(
            array(
                'Title'       => $this->Title,
                'Description' => $this->Description,
                'PicUrl'      => $this->PicUrl,
                'Url'         => $this->Url,
            )
        ), $this->_articles);
    }

    public function addArticle(array $data)
    {
        if ($this->getArticleCount() > 10) {
            return $this;
        }

        if (! Arr::is_assoc($data)) {
            foreach ($data as $item) {
                $this->addArticle($item);
            }

            return $this;
        }

        $this->_articles[] = array_intersect_key($data, array(
            'Title'       => NULL,
            'Description' => NULL,
            'PicUrl'      => NULL,
            'Url'         => NULL,
        ));

        return $this;
    }

    public function prepareXmlData()
    {
        $data = parent::prepareXmlData();

        $data['Articles'] = array(
            'item' => $data['Articles'],
        );

        return $data;
    }
}
