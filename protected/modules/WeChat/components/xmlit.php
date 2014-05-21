<?php

class xmlit
{

    public static function xml2array($xml)
    {
        try {
            $sxi = new SimpleXmlIterator($xml);

            return self::sxiToArray($sxi);
        }
        catch (Exception $e) {
            return array();
        }
    }

    /**
     * @param SimpleXMLIterator $sxi
     *
     * @return array
     */
    public static function sxiToArray($sxi)
    {
        $a = array();
        $attribs = (array) $sxi->attributes();
        if ($attribs) {
            $a = $attribs;
        }
        for ($sxi->rewind(); $sxi->valid(); $sxi->next()) {
            if (! array_key_exists($sxi->key(), $a)) {
                $a[$sxi->key()] = array();
            }
            if ($sxi->hasChildren()) {
                $a[$sxi->key()][] = self::sxiToArray($sxi->current());
            } else {
                if ($sxi->current()->attributes()) {
                    $a[$sxi->key()] = self::sxiToArray($sxi->current());
                    $a[$sxi->key()]['value'] = strval($sxi->current());
                } else {
                    $a[$sxi->key()] = strval($sxi->current());
                }
            }
        }

        return $a;
    }

    public static function array2xml($name, array $array)
    {
        $xml = new SimpleXMLElementEx('<'.$name.'/>');

        return xmlit::xmlAddChild($xml, $array);
    }

    public static function xmlAddChild(SimpleXMLElementEx $xml, $values)
    {
        foreach ($values as $key => $value) {
            if (! is_array($value)) {

                if (is_numeric($value)) {
                    $xml->addChild($key, $value);
                } else {
                    $xml->addChildCData($key, $value);
                }

                continue;
            }

            if (count($value) === 0) {
                continue;
            }

            if (Arr::is_assoc($value)) {
                // 关联数组，添加单个节点
                $node = $xml->addChild($key);
                self::xmlAddChild($node, $value);
            } else {
                //
                foreach ($value as $item) {
                    $node = $xml->addChild($key);
                    self::xmlAddChild($node, $item);
                }
            }
        }

        return $xml;
    }
}
