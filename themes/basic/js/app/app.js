define([
  'angular', 'route', 'angularAnimate', 'ngUi', 'ngUiRouter', 'ngUiSortable',

  'c/Auth', 'c/ConsoleMenu', 'c/Alert', 'c/Account', 'c/Response', 'c/Trigger',
  'c/Menu',

  's/apiHttpInterceptor'
], function(angular, route)
{
  var con = angular.module('Console', [
    'ui.router', 'ui.bootstrap', 'Controllers', 'Services', 'Directives',
    'ngAnimate', 'ui.sortable'
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