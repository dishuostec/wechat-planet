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
 * @property string $token
 * @property string $suffix
 * @property string $url
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
class Account extends ActiveRecord
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
            array('name', 'length', 'max' => 20),
            array('type', 'validField'),
            array('appid', 'length', 'max' => 18),
            array('appsecret', 'length', 'max' => 32),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'        => '原始ID',
            'name'      => '名称',
            'type'      => '类型',
            'appid'     => 'AppId',
            'appsecret' => 'AppSecret',
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

    public function getUrl()
    {
        return Mod::app()->createAbsoluteUrl('WeChat/Callback', array(
            'suffix' => $this->suffix,
        ));
    }

    public function changeSuffix()
    {
        do {
            $suffix = Text::random('alnum', 40);
        } while (Account::model()->exists('suffix=:suffix', array(
            ':suffix' => $suffix,
        )));

        $this->suffix = $suffix;
        $this->save();
        return $this;
    }

    public function changeToken()
    {
        do {
            $token = Text::random('alnum', 40);
        } while (Account::model()->exists('token=:token', array(
            ':token' => $token,
        )));

        $this->token = $token;
        $this->save();
        return $this;
    }

    public static function type($index = NULL)
    {
        $array = array(
            self::TYPE_SERVICE      => '服务号',
            self::TYPE_SUBSCRIPTION => '订阅号',
        );

        return is_null($index) ? $array : Arr::get($array, $index, '');
    }
}