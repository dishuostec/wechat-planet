<?php

/**
 * Class Response
 * @property number $id
 * @property string $account_id
 */
class Response extends ActiveRecord
{
    const TYPE_TEXT = 1;

    /**
     * @param $type
     * @param $id
     * @param $accountId
     * @return bool
     */
    public static function exist($type, $id, $accountId)
    {
        $className = self::className($type);

        if (empty($className)) {
            return FALSE;
        }

        /**
         * @var CActiveRecord $class
         */
        return $className::model()
            ->exists('id=:id and account_id=:account_id', array(
                ':id'         => $id,
                ':account_id' => $accountId,
            ));
    }

    public static function get($type, $id, $accountId)
    {
        $className = self::className($type);

        if (empty($className)) {
            return FALSE;
        }

        /**
         * @var CActiveRecord $className
         */
        return $className::model()
            ->findByAttributes(array(
                'id'         => $id,
                'account_id' => $accountId,
            ));
    }

    /**
     * 获取响应类的类名
     * @param $type
     * @return null|string
     */
    public static function className($type)
    {
        $type = intval($type);
        switch ($type) {
            case self::TYPE_TEXT:
                return 'ResponseText';
                break;
            default:
                return NULL;
        }
    }
}