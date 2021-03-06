<?php
// 定义根目录
define("ROOT_DIR", dirname(__FILE__));
require(ROOT_DIR."/protected/components/Environment.php");
// 创建环境配置对象
$env = new Environment(NULL, array('life_time' => 30));
// 设置输出编码，效果同php.ini中配置default_charset
header('Content-type:text/html;charset='.$env->get('charset'));
// 创建一个Web应用实例并执行
require($env->getModPath().'/Mod.php');
Mod::createWebApplication($env->getConfig())->run();
