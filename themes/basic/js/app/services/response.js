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

      var all_type = [
        'none', 'text'
      ];

      var service = {
        type  : function(type)
        {
          return type ? all_type[+ type] : all_type;
        },
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
          return $api$.post('response/' + type, data).success(function(response)
          {
            responseList[type].unshift(response);
          });
        },
        update: function(type, data)
        {
          var id = data.id;

          return $api$.put('response/' + type + '/' + id,
          data).success(function()
          {
            service.get(type, id).then(function(response)
            {
              angular.copy(data, response);
            });
          });
        },
        remove: function(type, data)
        {
          return $api$.delete('response/' + type + '/' + data.id);
        }
      };

      return service;
    }
  ]);
});