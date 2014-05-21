<?php
return array(
    'components' => array(
        'log'   => array(
            'routes' => array(
                array(
                    'class'       => 'CFileLogRoute', // 写入
                    'levels'      => 'error', // 记录所有级别的
                    'LogDir'      => LOG_DIR, //此目录可配置,在此目录下，每天一个文件夹
                    'logFileName' => 'error.log' //记录日志的文件名可配置
                ),
                array(
                    'class'       => 'CFileLogRoute', // 写入
                    'levels'      => '', // 记录所有级别的
                    'LogDir'      => LOG_DIR, //此目录可配置,在此目录下，每天一个文件夹
                    'logFileName' => 'all.log' //记录日志的文件名可配置
                ),
            ),
        ),
        'db'    => array(
            'nameServiceKeyMaster'  => '',
            'forceMaster'           => TRUE,
            'connectionString'      => 'mysql:host={{host}};port={{port}};dbname=',
            'username'              => '',
            'password'              => '',
            'nameServiceKeysSlave'  => NULL,
            'slaveConfigs'          => array(),
            'enableParamLogging'    => TRUE,
            // 开启表结构缓存（schema caching）提高性能
            'schemaCachingDuration' => 0,
        ),
        'cache' => array(
            'class'               => 'CRedisCache',
            'nameServiceKeys'     => array(
                'sealsIDC' => '',
            ),
            'serverConfigs'       => array(
                'sealsIDC' => array('host' => '', 'port' => 0)
            ),
            'getIDC'              => array('sealsIDC'), //读操作时，按顺序尝试
            'setIDC'              => array('sealsIDC'), //写操作时，按顺序尝试
            'localIDC'            => 'sealsIDC', //本地IDC机房
            'getRetryCount'       => 2, //读操作重试次数2次
            'getRetryInterval'    => 0, //读操作重试间隔0秒
            'setRetryCount'       => 3, //写操作重试次数3次
            'setRetryInterval'    => 0.2, //写操作重试间隔0.2秒
            'enablePerformReport' => FALSE //上报性能数据
        ),
    ),
);
?>
