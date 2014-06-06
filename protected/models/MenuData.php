<?php

/**
 * Class MenuData
 * @property string $account_id
 * @property string $data
 */
class MenuData extends CActiveRecord
{
    /**
     * @param string $className
     * @return MenuData
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return "{{account_menu}}";
    }
}
