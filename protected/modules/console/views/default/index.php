<?php
/**
 * @var CController $this
 */

$assetsPath = Mod::app()->theme->baseUrl;
?>
<!doctype html>
<html lang="cn">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo CHtml::encode(Mod::app()->name); ?></title>
<link rel="stylesheet" href="<?php echo $assetsPath; ?>/css/bootstrap.min.css"/>
<link rel="stylesheet" href="<?php echo $assetsPath; ?>/css/console.css"/>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" ng-controller="Menu">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" ng-click="isCollapsed = !isCollapsed">
                <span class="sr-only">顶部导航</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" ui-sref="welcome">管理后台</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" collapse="isCollapsed">
            <ul class="nav navbar-nav">
                <li ui-sref-active="active" ng-repeat="(state, caption) in menu">
                    <a ui-sref="{{state}}" ng-bind="caption"></a></li>
            </ul>
            <a ng-if="!user.id" ui-sref="login" class="btn btn-success navbar-btn navbar-right">登录</a>
            <a ng-if="user.id" ng-click="logout()" class="btn btn-default navbar-btn navbar-right">注销</a>

            <p ng-if="user.id" class="navbar-text navbar-right" ng-bind="user.name"></p>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
<div ng-controller="Alert">
    <alert ng-repeat="alert in alerts" type="alert.type" close="remove(alert.id)">
        {{alert.msg}}
    </alert>
</div>
<div class="container-fluid" ui-view></div>

<script type="text/javascript" data-main="<?php echo $assetsPath; ?>/js/main.js" src="<?php echo $assetsPath; ?>/js/vendor/require.js"></script>
</body>
</html>
