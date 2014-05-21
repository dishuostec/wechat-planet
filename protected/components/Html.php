<?php

class Html extends CHtml
{

    public static function radioField($name, $value, $select,
                                      $htmlOptions = array())
    {
        if ($select) {
            $htmlOptions['checked'] = 'checked';
        }

        return self::inputField('radio', $name, $value, $htmlOptions);
    }
}