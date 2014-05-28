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
            array('account', 'required', 'on' => 'create'),
            array('keyword, response_type, response_id', 'required'),
            array('keyword', 'length', 'max' => 255),
            array('response_id', 'responseExist'),
        );
    }
}