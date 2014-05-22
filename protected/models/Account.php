<?php

/**
 * Class Account
 * 公众号模型
 *
 * @property string $id 公众号原始ID
 * @property string $name
 * @property number $type
 * @property string $appid
 * @property string $appsecret
 * @property number $root_manager_id
 *
 * @property bool $isService
 * @property bool $isSubscription
 * @property bool $isCertified
 * @property bool $isCurrent
 *
 * 关系模型
 * @property Manager $rootManager
 * @property Array $managers
 */
class Account extends CActiveRecord
{
    const TYPE_SERVICE = 1;
    const TYPE_SUBSCRIPTION = 2;

    /**
     * @param string $className
     * @return Account
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return "{{account}}";
    }

    public static function extractFields()
    {
        return array(
            'id',
            'name',
            'isService',
            'isSubscription',
            'isCertified',
            'isCurrent',
        );
    }

    public function rules()
    {
        return array(
            array('id, name, type, root_manager_id', 'required', 'on' => 'create'),
            array('name, type', 'required', 'on' => 'update'),
            array('appid', 'length', 'min' => 1, 'max' => 18),
            array('appsecret', 'length', 'min' => 1, 'max' => 32),
        );
    }

    public function relations()
    {
        return array(
            'rootManager' => array(
                self::BELONGS_TO, 'Manager', array('id' => 'root_manager_id')
            ),
            'managers'    => array(
                self::MANY_MANY, 'Manager', '{{manager_account}}(manager_id, account_id)',
                'condition' => 'managers.is_banded = 0',
            ),
        );
    }

    public function getIsService()
    {
        return $this->type == self::TYPE_SERVICE;
    }

    public function getIsSubscription()
    {
        return $this->type == self::TYPE_SUBSCRIPTION;
    }

    public function getIsCertified()
    {
        return ! (empty($this->appid) || empty($this->appsecret));
    }

    public function getIsCurrent()
    {
        return Mod::app()->Auth->isCurrentAccount($this);
    }
}