<?php

abstract class WechatData extends CModel
{
    private static $_names = array();

    protected $_xml_string = '';

    public $MsgType = '';
    public $ToUserName;
    public $FromUserName;
    public $CreateTime; //是，消息创建时间 （整型）
    public $MsgId; //无，消息id，64位整型

    public function __construct($scenario = '')
    {
        $this->setScenario($scenario);
        $this->init();
        $this->attachBehaviors($this->behaviors());
        $this->afterConstruct();
    }

    public function init()
    {
    }

    protected function setMsgType($value)
    {
        return FALSE;
    }

    public function values($data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function attributeNames()
    {
        $className = get_class($this);
        if (! isset(self::$_names[$className])) {
            $class = new ReflectionClass(get_class($this));
            $names = array();
            foreach ($class->getProperties() as $property) {
                $name = $property->getName();
                if ($property->isPublic() && ! $property->isStatic()) {
                    $names[] = $name;
                }
            }

            return self::$_names[$className] = $names;
        } else {
            return self::$_names[$className];
        }
    }

    public function asArray()
    {
        $array = array();

        foreach ($this->attributeNames() as $key) {
            $array[$key] = $this->$key;
        }

        return $array;
    }

    public function setOriginXml($value)
    {
        return $this->_xml_string = $value;
    }

    public function getOriginXml()
    {
        return $this->_xml_string;
    }
}