<?php
return array(
    'urlFormat'      => 'path',
    //要不要显示url中的index.php
    'showScriptName' => FALSE,
    //url对应的解析规则,类似于nginx和apache的rewite,支持正则
    'rules'          => array(
        // 微信回调
        array(
            'WeChat/Callback',
            'pattern' => 'wechat/cb/<suffix:[a-zA-Z0-9]+>',
        ),
        // 控制台api
        array(
            'api/<_m>',
            'pattern' => 'api/<_m:\w+>(?:/<id:\d+|\w{10,}>)?',
        ),
        array(
            'api/<_m>/<_r>',
            'pattern' => 'api/<_m:\w+>/<_r:\w+>(?:/<id:\d+|\w{10,}>)?',
        ),
        // 控制台模块默认页面
        'console/<any:.*>'  => 'console/default/index',
        // 默认路由
        '<_c:\w+>/<_a:\w+>' => '<_c>/<_a>',
    ),
);