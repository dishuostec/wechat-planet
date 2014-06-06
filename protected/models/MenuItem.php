<?php

/**
 * Class MenuItem
 * @property number $id
 * @property string $account_id
 * @property string $name 名称
 * @property string $caption 菜单标题
 */
class MenuItem extends CActiveRecord
{
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

    public static function extractFields()
    {
        return array(
            'id',
            'name',
            'caption',
            'response_type',
            'response_id',
        );
    }
}
