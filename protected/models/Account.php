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
 * @property array $menu
 *
 * 关系模型
 * @property Manager $rootManager
 * @property Array $managers
 * @property Array $menuItems
 * @property MenuData $menuData
 * @property Array $responseText
 * @property Array $triggerText
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
            'rootManager'  => array(
                self::BELONGS_TO, 'Manager', array('id' => 'root_manager_id')
            ),
            'managers'     => array(
                self::MANY_MANY, 'Manager', '{{manager_account}}(manager_id, account_id)',
                'condition' => 'managers.is_banded = 0',
            ),
            'menuItems'    => array(
                self::HAS_MANY, 'MenuItem', 'account_id',
            ),
            'menuData'     => array(
                self::HAS_ONE, 'MenuData', 'account_id',
            ),
            'responseText' => array(
                self::HAS_MANY, 'ResponseText', 'account_id',
                'order' => 'id DESC',
            ),
            'triggerText'  => array(
                self::HAS_MANY, 'TriggerText', 'account_id'
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

    /**
     * @param array $menu
     */
    public function setMenu(array $menu)
    {
        $menuData = $this->menuData;
        if (is_null($menuData)) {
            $menuData = new MenuData();
            $menuData->account_id = $this->id;
        }
        $menuData->data = serialize($menu);
        $menuData->save();
    }

    /**
     * @return array
     */
    public function getMenu()
    {
        $data = unserialize($this->menuData->data);
        return $data === FALSE ? array() : $data;
    }

    /**
     * @param $id
     * @return MenuItem|null
     */
    public function fetchMenuItem($id)
    {
        if (empty($id)) {
            return NULL;
        }

        return array_pop($this->menuItems(array(
            'condition' => 'id=:id',
            'params'    => array(
                ':id' => $id,
            ),
            'limit'     => 1,
        )));
    }

    public function fetchResponseText($id)
    {
        if (empty($id)) {
            return NULL;
        }

        return array_pop($this->responseText(array(
            'condition' => 'id=:id',
            'params'    => array(
                ':id' => $id,
            ),
            'limit'     => 1,
        )));
    }

    public function fetchTriggerText($id)
    {
        if (empty($id)) {
            return NULL;
        }

        return array_pop($this->triggerText(array(
            'condition' => 'id=:id',
            'params'    => array(
                ':id' => $id,
            ),
            'limit'     => 1,
        )));
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
            $token = Text::random('alnum', 32);
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