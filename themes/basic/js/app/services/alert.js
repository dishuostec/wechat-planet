define(['angular', 'service'], function(angular, Service)
{
  Service.factory('$alert$', [
    '$timeout', '$rootScope', function($timeout, $rootScope)
    {
      var count = 0;
      var alerts_list = [];
      var uid = function()
      {
        return count ++;
      };
      var add = function(msg, type)
      {
        var id = uid();

        alerts_list.push({
          id  : id,
          msg : msg,
          type: type
        });

        return id;
      };

      var clear = function()
      {
        alerts_list = [];
        console.info('[s]alert clear');
      };

      var remove = function(id)
      {
        var new_list = [];
        angular.forEach(alerts_list, function(alert)
        {
          if (alert.id === id) {
            return;
          }

          this.push(alert);
        }, new_list);
        alerts_list = new_list;
      };

      $rootScope.$watch(function()
      {
        return angular.forEach(alerts_list, function(alert)
        {
          return alert.id;
        });
      }, function()
      {
        $rootScope.$emit('Alert', alerts_list);
      });

      var service = {
        remove: remove,
        clear : clear
      };

      angular.forEach({
        success: 'success',
        info   : 'info',
        warn   : 'warning',
        error  : 'danger'
      }, function(type, func)
      {
        service[func] = function(msg, timeout)
        {
          var id = add(msg, type);

          if (timeout) {
            $timeout(function()
            {
              remove(id);
            }, timeout === true ? 3000 : timeout);
          }
        };
      });

      return service;
    }
  ]);
});