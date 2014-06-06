<?php

/**
 * Class TriggerText
 * @property string $keyword
 */
class TriggerText extends Trigger
{
    /**
     * @param string $className
     * @return TriggerText
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return "{{trigger_text}}";
    }

    public function rules()
    {
        return array(
            array('account_id', 'required', 'on' => 'create'),
            array('keyword', 'required'),
            array('keyword', 'length', 'max' => 255),
            array('response_type', 'length', 'min' => 1),
            array('response_id', 'length', 'min' => 1),
            array('response_id', 'responseExist'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'keyword' => '关键字',
        );
    }
}