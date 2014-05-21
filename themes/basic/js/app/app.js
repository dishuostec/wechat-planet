define([
  'angular', 'route', 'ngUi', 'ngUiRouter',

  'c/Auth', 'c/Menu', 'c/Alert',

  's/apiHttpInterceptor'
], function(angular, route)
{
  var con = angular.module('Console',
  ['ui.router', 'ui.bootstrap', 'Controllers', 'Services', 'Directives'])

    // url设置
  .config([
    '$locationProvider', function($locationProvider)
    {
      $locationProvider.html5Mode(true).hashPrefix('!');
    }
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