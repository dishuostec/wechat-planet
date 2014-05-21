<?php

/**
 * Class Manager
 * @property string $open_id
 */
class Manager extends CActiveRecord
{
    /**
     * @param string $className
     * @return Manager
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return "{{manager}}";
    }

    public function attributeLabels()
    {
        return array(
            'id'        => 'QQ',
            'name'      => '姓名',
            'is_banded' => '已禁用',
        );
    }

    public function relations()
    {
        return array();
    }

    public function verified()
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'is_banded = 0',
        ));

        return $this;
    }

    public function rules()
    {
        return array(
            array('id,name', 'required', 'message' => '必填项'),
            array('id', 'numerical', 'min' => 10000, 'max' => 9999999999999, 'tooSmall' => '无效的QQ号码', 'tooBig' => '无效的QQ号码'),
        );
    }

    public function insert($attributes = NULL)
    {
        $this->create_at = time();
        return parent::insert($attributes);
    }

    public function update($attributes = NULL)
    {
        $this->update_at = time();
        return parent::update($attributes);
    }
}