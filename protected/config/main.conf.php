<?php
/*
 *CWebapplication的配置文件,所有的配置都在此配置
 */
define('CONFIG_PATH', realpath(dirname(__FILE__)));
define('LOG_DIR', realpath(CONFIG_PATH.'/../runtime/logs'));
return array(
    'params'            => array(
        // CGI性能上报开关
        'enableCgiPerform' => TRUE,
        'enableUriXss'     => TRUE,
        'modPath'          => '/data/php/framework',
    ),
    'basePath'          => CONFIG_PATH.DIRECTORY_SEPARATOR.'..',
    // 应用中文名
    'name'              => '微信运营平台',
    // 应用英文名，用来唯一标识应用
    'id'                => 'we_chat_platform',
    // 应用编码（html、db等）
    'charset'           => 'utf-8',
    // 语言包选择，默认为英语
    'language'          => 'zh_cn',
    // 预加载的组件，
    'preload'           => array('log'),
    // 主题
    'theme'             => 'basic',
    // 默认导入类型
    'import'            => array(
        /**
         * application表示webroot的protected目录路径
         */
        'application.models.*',
        'application.components.*',
    ),
    // 默认的controller,
    'defaultController' => 'site',

    // 组件配置, 通过key引用（如：Mod::app()->bootstrap);
    'components'        => array(
        //url管理组件
        'urlManager' => require(CONFIG_PATH.'/url.conf.php'),
        // 日志配置，必须预加载生效
        'log'        => array(
            'class'  => 'CLogRouter',
            'routes' => array(
                array(
                    'class'       => 'CFileLogRoute', // 写入
                    'levels'      => '', // 记录所有级别的
                    'LogDir'      => LOG_DIR, //此目录可配置,在此目录下，每天一个文件夹
                    'logFileName' => 'all.log' //记录日志的文件名可配置
                )
            ),
        ),
        'db'         => array(
            'class'                 => 'CDbConnection',
            'charset'               => 'utf8',
            'tablePrefix'           => 'wp_',
            // 主库
            'nameServiceKeyMaster'  => '',
            'connectionString'      => 'mysql:host={{host}};port={{port}};dbname=',
            'username'              => '',
            'password'              => '',
            // 从库
            'nameServiceKeysSlave'  => array(''),
            'slaveConfigs'          => array(
                array('connectionString' => 'mysql:host={{host}};port={{port}};dbname=', 'username' => '', 'password' => ''),
            ),
            'enableParamLogging'    => MOD_DEBUG ? TRUE : FALSE,
            //重试3次，每次间隔0.1秒
            'retryCount'            => 3,
            'retryInterval'         => 0.1,
            //主从延时0.5秒
            'slaveSyncDelay'        => 0.5,
            // 开启表结构缓存（schema caching）提高性能
            'schemaCachingDuration' => 3600,
        ),
        'cache'      => array(
            'class'               => 'CRedisCache',
            'nameServiceKeys'     => array(
                'sealsIDC' => '',
            ),
            'serverConfigs'       => array(
                'sealsIDC' => array('host' => '', 'port' => 0),
            ),
            'getIDC'              => array('sealsIDC'), //读操作时，按顺序尝试
            'setIDC'              => array('sealsIDC'), //写操作时，按顺序尝试
            'localIDC'            => 'sealsIDC', //本地IDC机房
            'getRetryCount'       => 2, //读操作重试次数2次
            'getRetryInterval'    => 0, //读操作重试间隔0秒
            'setRetryCount'       => 3, //写操作重试次数3次
            'setRetryInterval'    => 0.2, //写操作重试间隔0.2秒
            'enablePerformReport' => TRUE //上报性能数据
        ),
        'user'       => array(
            'class' => 'CPtUser',
        ),
        'Auth'       => array(
            'class' => 'Auth',
        ),
        'Cookie'     => array(
            'class' => 'Cookie',
            'salt'  => 'da_yu_wang',
        ),
    ),
    'modules'           => array(
        'WeChat'  => array(),
        'api'     => array(
            'modules' => array(
                'response' => array(), //响应
                'trigger'  => array(), //请求
            ),
        ),
        'console' => array(),
    ),
);
