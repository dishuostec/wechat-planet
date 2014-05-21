define(['module'], function(module)
{
  var baseUrl = module.config().baseUrl;
  var baseTplUrl = module.config().baseTplUrl;

  return [
    '$stateProvider', '$urlRouterProvider',
    function($stateProvider, $urlRouterProvider)
    {
      $urlRouterProvider.otherwise('/console');

      $stateProvider

      .state('foo', {
        url        : baseUrl,
        templateUrl: baseTplUrl + '/welcome.html'
      })

      .state('welcome', {
        url        : baseUrl,
        templateUrl: baseTplUrl + '/welcome.html'
      })

      .state('login', {
        url        : baseUrl + '/auth',
        templateUrl: baseTplUrl + '/auth/login.html'
      });
    }
  ];
});