define(['angular', 'service', 's/api'], function(angular, Service)
{
  Service.factory('$response$', [
    '$rootScope', '$api$', '$q', function($rootScope, $api$, $q)
    {
      var promise = {};
      var responseList = {
        text: []
      };

      var getIndex = function(list, id)
      {
        var n = list.length;
        while (n --) {
          if (list[n].id === id) {
            return n;
          }
        }

        return null;
      };

      var service = {
        get   : function(type, id)
        {
          return service.list(type).then(function(list)
          {
            var index = getIndex(list, id);
            return (index === null ? $q.reject(null) : list[index]);
          });
        },
        list  : function(type, forceUpdate)
        {
          if (forceUpdate || ! angular.isDefined(promise[type])) {
            var defer = $q.defer();

            $api$.get('response/' + type).success(function(list)
            {
              angular.copy(list, responseList[type]);
              defer.resolve(responseList[type]);
            });
            promise[type] = defer.promise;
          }

          return promise[type];
        },
        add   : function(type, data)
        {
          var defer = $q.defer();

          $api$.post('response/' + type, data).success(function(response)
          {
            responseList[type].unshift(response);
            defer.resolve(response);
          });

          return defer.promise;
        },
        update: function(type, data)
        {
          var defer = $q.defer();
          var id = data.id;

          $api$.put('response/' + type + '/' + id, data).success(function()
          {
            service.get(type, id).then(function(response)
            {
              angular.copy(data, response);
            });
            defer.resolve();
          });

          return defer.promise;
        },
        remove: function(type, data)
        {
          var defer = $q.defer();

          $api$.delete('response/' + type + '/' + data.id).success(function()
          {
            defer.resolve();
          });

          return defer.promise;
        }
      };

      return service;
    }
  ]);
});