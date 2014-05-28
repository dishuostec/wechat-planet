<?php

class Response
{
    const TYPE_TEXT = 1;

    public static function exist($type, $id, $accountId)
    {
        switch ($type) {
            case self::TYPE_TEXT:
                $class = 'ResponseType';
                break;
            default:
                return FALSE;
        }

        /**
         * @var CActiveRecord $class
         */
        return $class::model()->exists(array(
            'id'         => $id,
            'account_id' => $accountId,
        ));
    }
}