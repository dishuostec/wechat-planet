<?php

/**
 * Class MenuItem
 * @property number $id
 * @property string $account_id
 * @property string $name 名称
 * @property string $caption 菜单标题
 * @property number $type
 * @property string $url
 * @property number response_type
 * @property number response_id
 */
class MenuItem extends CActiveRecord
{
    const TYPE_LINK = 1;
    const TYPE_BUTTON = 2;

    /**
     * @param string $className
     * @return MenuItem
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return "{{menu_item}}";
    }

    public function rules()
    {
        return array(
            array('account_id', 'required', 'on' => 'create'),
            array('type', 'typeValid'),
        );
    }

    public function typeValid()
    {
        $type = intval($this->type);
        switch ($type) {
            case self::TYPE_LINK:
                if (empty($this->url)) {
                    $this->addError('url', '请填写链接');
                }
                break;
            case self::TYPE_BUTTON:
                if (! Response::exist($this->response_type, $this->response_id,
                    $this->account_id)
                ) {
                    $this->addError('response_id', '指定的响应不存在');
                }
                break;
            default:
                $this->addError('type', '类型错误');
        }
    }

    public static function extractFields()
    {
        return array(
            'id',
            'name',
            'caption',
            'type',
            'url',
            'response_type',
            'response_id',
        );
    }
}
