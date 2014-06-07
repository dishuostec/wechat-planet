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
<block ng-include="'<?php echo $assetsPath; ?>/template/console.menu.html'"></block>
<block ng-include="'<?php echo $assetsPath; ?>/template/alert.html'"></block>
<div class="container-fluid" ui-view></div>
<script type="text/javascript" data-main="<?php echo $assetsPath; ?>/js/main.js" src="<?php echo $assetsPath; ?>/js/vendor/require.js"></script>
</body>
</html>
