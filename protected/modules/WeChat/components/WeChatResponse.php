<?php

class WeChatResponse
{
    /**
     * @param Response $response
     * @param WechatData $request
     * @return null|WechatMessage
     */
    public static function factory(Response $response,
                                   WechatData $request = NULL)
    {
        if (! $response instanceof Response) {
            return NULL;
        }

        $type = get_class($response);
        $type = strtolower(substr($type, 8));

        $message = WeChat::createResponseDataObject($type);

        if ($message instanceof WechatDataUnknown) {
            return NULL;
        }

        $message->setScenario('autofill');
        $message->attributes = $response->attributes;

        return $message;
    }
}