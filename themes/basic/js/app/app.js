define([
  'angular', 'route', 'angularAnimate', 'ngUi', 'ngUiRouter',

  'c/Auth', 'c/Menu', 'c/Alert', 'c/Account', 'c/Response', 'c/Trigger',

  's/apiHttpInterceptor'
], function(angular, route)
{
  var con = angular.module('Console', [
    'ui.router', 'ui.bootstrap', 'Controllers', 'Services', 'Directives',
    'ngAnimate'
  ])

    // 全局错误处理
  .config([
    '$httpProvider', function($httpProvider)
    {
      $httpProvider.interceptors.push('$apiHttpInterceptor$');
    }
  ])

    // 路由
  .config(route);

  angular.element(document).ready(function()
  {
    angular.bootstrap(document, ['Console']);
  });

  return con;
});