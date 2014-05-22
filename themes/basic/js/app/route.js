define(['config'], function(config)
{
  var baseUrl = config.baseUrl;
  var baseTplUrl = config.baseTplUrl;

  return [
    '$stateProvider', '$urlRouterProvider', '$locationProvider',
    function($stateProvider, $urlRouterProvider, $locationProvider)
    {
      $locationProvider.html5Mode(true).hashPrefix('!');

      $urlRouterProvider.otherwise(baseUrl);

      $stateProvider

      .state('foo', {
        url        : baseUrl + '/foo',
        templateUrl: baseTplUrl + '/welcome.html'
      })

      .state('account', {
        url        : baseUrl + '/account/:accountId',
        templateUrl: baseTplUrl + '/account/config.html'
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