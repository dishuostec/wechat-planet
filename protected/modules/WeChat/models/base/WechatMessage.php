<?php

abstract Class WechatMessage extends WechatData
{
    protected $_json_format = array();
    protected $_xml_format = array();

    /**
     * 回复系统事件，xml格式
     *
     * @return string
     */
    public function asXml()
    {
        $data = $this->prepareXmlData();

        $xml = xmlit::array2xml('xml', $data);

        $xmlString = $xml->asXML();

        $xmlString = preg_replace('/^<\?xml[^>]*>*\s*/', '', $xmlString);

        return $xmlString;
    }

    /**
     * 客服消息，json格式
     *
     * @return string
     */
    public function asJson()
    {
        $json = new stdClass();

        $this->_jsonAddChild($json, $this->asArray(), $this->_json_format);

        return json_encode($json);
    }

    protected function _jsonAddChild(stdClass $json, $data, $keys)
    {
        foreach ($keys as $jsonKey => $dataKey) {
            if (! is_array($dataKey)) {
                $json->$jsonKey = $data[$dataKey];
                continue;
            }

            $data = $this->$jsonKey;

            if (Arr::is_assoc($dataKey)) {
                /**
                 * 添加单个节点
                 *  array(
                 *      'text' => array(
                 *          'content' => 'Content',
                 *          'title' => 'Title',
                 *      ),
                 *  )
                 */
                $sub = $json->$jsonKey = new stdClass();
                $this->_jsonAddChild($sub, $data, $dataKey);
            } else {
                /**
                 * 多个节点
                 * array(
                 *      'music' => array(
                 *          array(
                 *              'title' => 'Title',
                 *          ),
                 *      },
                 * );
                 */
                $dataKey = $dataKey[0];
                $subArray = array();
                foreach ($data as $subData) {
                    $sub = new stdClass();
                    $this->_jsonAddChild($sub, $subData, $dataKey);
                    $subArray[] = $sub;
                }
                $json->$jsonKey = $subArray;
            }
        }
    }

    /**
     * @return array
     */
    protected function prepareXmlData()
    {
        $data = array();

        if (empty($this->_xml_format)) {
            $data = $this->asArray();

            return $data;
        } else {
            foreach ($this->_xml_format as $key => $val) {
                if (is_integer($key)) {
                    $key = $val;
                }
                $data[$key] = $this->$key;
            }

            return $data;
        }
    }
}