define(['angular', 'service', 'config'], function(angular, Service, config)
{
  Service.factory('$api$', [
    '$http', function($http)
    {
      var apiPath = config.baseApi + '/';

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
