define(['config', 'h/string'], function(config, string)
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

      .state('trigger', {
        url        : baseUrl + '/trigger',
        templateUrl: baseTplUrl + '/trigger/index.html',
        controller : 'Trigger'
      })

      .state('trigger.type', {
        url        : '/{type}',
        templateUrl: function($stateParams)
        {
          return baseTplUrl + '/trigger/' + $stateParams.type + '.list.html';
        },
        controller : 'TriggerList'
      })

      .state('trigger.type.edit', {
        url    : '/{id}',
        onEnter: function($stateParams, $state, $modal, $rootScope)
        {
          var type = $stateParams.type;
          var tplUrl = baseTplUrl + '/trigger/' + type + '.edit.html';

          var modal = $modal.open({
            size       : 'lg',
            templateUrl: tplUrl,
            controller : 'TriggerEdit',
            keyboard   : false,
            backdrop   : 'static'
          });

          var off = $rootScope.$on('$stateChangeStart', function()
          {
            modal.dismiss('route change');
          });

          modal.result.finally(function()
          {
            off();
            return $state.go('^');
          })
        }
      })

      .state('response', {
        url        : baseUrl + '/response',
        templateUrl: baseTplUrl + '/response/index.html',
        controller : 'Response'
      })

      .state('response.type', {
        url        : '/{type}',
        templateUrl: function($stateParams)
        {
          return baseTplUrl + '/response/' + $stateParams.type + '.list.html';
        },
        controller : 'ResponseList'
      })

      .state('response.type.edit', {
        url    : '/{id}',
        onEnter: function($stateParams, $state, $modal, $rootScope)
        {
          var type = $stateParams.type;
          var tplUrl = baseTplUrl + '/response/' + type + '.edit.html';

          var modal = $modal.open({
            templateUrl: tplUrl,
            controller : 'ResponseEdit',
            keyboard   : false,
            backdrop   : 'static'
          });

          var off = $rootScope.$on('$stateChangeStart', function()
          {
            modal.dismiss('route change');
          });

          modal.result.finally(function()
          {
            off();
            return $state.go('^');
          })
        }
      })

      .state('menu', {
        url        : baseUrl + '/menu',
        templateUrl: baseTplUrl + '/menu/items.html',
        controller : 'Menu'
      })

      .state('account', {
        url        : baseUrl + '/account/:accountId',
        templateUrl: baseTplUrl + '/account/config.html',
        controller : 'Account'
      })

      .state('welcome', {
        url        : baseUrl,
        templateUrl: baseTplUrl + '/welcome.html'
      })

      .state('login', {
        url        : baseUrl + '/auth',
        templateUrl: baseTplUrl + '/auth/login.html',
        controller : 'Auth'
      });
    }
  ];
});