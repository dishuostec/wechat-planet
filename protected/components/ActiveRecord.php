<?php

class ActiveRecord extends CActiveRecord
{

    /**
     * @param string $attribute the name of the attribute to be validated
     * @param array $params options specified in the validation rule
     * @throws ErrorException
     */
    public function validField($attribute, $params)
    {
        $class = get_class($this);

        if (! method_exists($class, $attribute)) {
            Mod::log(sprintf('类 %s 缺少静态方法 %s，验证规则无效', $class, $attribute),
                CLogger::LEVEL_ERROR, 'application');
            throw new ErrorException('字段对应的方法不存在'.$class);
        }

        $validData = call_user_func(array($class, $attribute));

        if (! array_key_exists($this->$attribute, $validData)) {
            $this->addError($attribute, Arr::get($params, 'message', '无效的数据'));
        }
    }
}