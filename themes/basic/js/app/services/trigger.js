define(['angular', 'service', 's/api'], function(angular, Service)
{
  Service.factory('$trigger$', [
    '$rootScope', '$api$', '$q', function($rootScope, $api$, $q)
    {
      var promise = {};
      var triggerList = {
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

            $api$.get('trigger/' + type).success(function(list)
            {
              angular.copy(list, triggerList[type]);
              defer.resolve(triggerList[type]);
            });
            promise[type] = defer.promise;
          }

          return promise[type];
        },
        add   : function(type, data)
        {
          var defer = $q.defer();

          $api$.post('trigger/' + type, data).success(function(trigger)
          {
            triggerList[type].unshift(trigger);
            defer.resolve(trigger);
          });

          return defer.promise;
        },
        update: function(type, data)
        {
          var defer = $q.defer();
          var id = data.id;

          $api$.put('trigger/' + type + '/' + id, data).success(function()
          {
            service.get(type, id).then(function(trigger)
            {
              angular.copy(data, trigger);
            });
            defer.resolve();
          });

          return defer.promise;
        },
        remove: function(type, data)
        {
          var defer = $q.defer();

          $api$.delete('trigger/' + type + '/' + data.id).success(function()
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