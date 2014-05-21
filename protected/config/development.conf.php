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
            'connectionString'      => 'mysql:host=localhost;port=3306;dbname=',
            'username'              => '',
            'password'              => '',
            'nameServiceKeysSlave'  => NULL,
            'slaveConfigs'          => array(),
            'enableParamLogging'    => TRUE,
            // 开启表结构缓存（schema caching）提高性能
            'schemaCachingDuration' => 0,
        ),
        'cache' => array(
            'class'                => 'application.components.FileCache',
            'nameServiceKeyMaster' => NULL,
            'nameServiceKeysSlave' => NULL,
        ),
        'user'  => array(
            'class' => 'FakePtUser',
        ),
    ),
);
?>
