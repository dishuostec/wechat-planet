<?php

/**
 * Class ResponseText
 * @property string $content
 */
class ResponseText extends Response
{
    /**
     * @param string $className
     * @return ResponseText
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return "{{response_text}}";
    }

    public function rules()
    {
        return array(
            array('account', 'required', 'on' => 'create'),
            array('content', 'required'),
            array('content', 'length', 'max' => 255),
        );
    }
}