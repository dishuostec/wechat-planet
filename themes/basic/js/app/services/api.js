define(['angular', 'service'], function(angular, Service)
{
  Service.factory('$api$', [
    '$http', function($http)
    {
      var apiPath = '/console/api/';

      var api = {};

      angular.forEach(['get', 'put', 'post', 'delete'], function(method)
      {
        this[method] = function(module, data)
        {
          return $http[method](apiPath + module, data);
        };
      }, api);

      return api;
    }
  ]);
});
