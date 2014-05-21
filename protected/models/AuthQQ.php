<?php

/**
 * Class AuthQQ
 * @property number $uin
 * @property number $manager_id
 * @property Manager $manager
 */
class AuthQQ extends CActiveRecord
{
    /**
     * @param string $className
     * @return AuthQQ
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return "{{auth_qq}}";
    }

    public function relations()
    {
        return array(
            'manager' => array(
                self::BELONGS_TO, 'Manager', array('manager_id' => 'id'),
            ),
        );
    }
}
