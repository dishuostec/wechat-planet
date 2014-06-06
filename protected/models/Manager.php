<?php

/**
 * Class Manager
 * 后台管理员模型
 *
 * @property string $open_id
 *
 * 关系模型
 * @property Array $accounts 关联的公众号
 * @property Account $currentAccount 当前操作的公众号
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
        return array(
            'accounts' => array(
                self::MANY_MANY, 'Account', '{{manager_account}}(manager_id, account_id)',
            ),
        );
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

    /**
     * 获取指定的关联公众号
     *
     * @param $account_id
     * @param bool $returnDefault
     * @return mixed|null
     */
    public function fetchAccount($account_id, $returnDefault = FALSE)
    {
        if (empty($account_id)) {
            $object = NULL;
        } else {
            $object = array_pop($this->accounts(array(
                'condition' => 'accounts.id=:id',
                'params'    => array(
                    ':id' => $account_id,
                ),
                'limit'     => 1,
            )));
        }

        if (is_null($object) && $returnDefault) {
            $object = array_pop($this->accounts(array(
                'limit' => 1,
            )));
        }

        return $object;
    }
}