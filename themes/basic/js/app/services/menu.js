define(['angular', 'service', 's/api'], function(angular, Service)
{
  Service.factory('$menu$', [
    '$rootScope', '$api$', '$q', function($rootScope, $api$, $q)
    {
      var menuItems = [];
      var menuData = [];

      var dataPromise;
      var menuService = {
        get    : function(forceUpdate)
        {
          if (forceUpdate || ! angular.isDefined(dataPromise)) {
            var defer = $q.defer();

            $q.all({
              'menu' : $api$.get('menu'),
              'items': itemService.list(forceUpdate)
            }).then(function(data)
            {
              var menus = [
                [],
                [],
                []
              ];

              var promiseList = [];

              angular.forEach(data.menu.data, function(menu, menuIndex)
              {
                var items = [];
                angular.forEach(menu, function(itemId, itemIndex)
                {
                  promiseList.push(itemService.get(itemId).then(function(item)
                  {
                    menus[menuIndex][itemIndex] = item;
                  }));
                });
              });

              $q.all(promiseList).then(function()
              {
                angular.copy(menus, menuData);
                defer.resolve(menuData);
              })
            });

            dataPromise = defer.promise;
          }

          return dataPromise;
        },
        update : function()
        {
          var data = [];

          angular.forEach(menuData, function(menu, index)
          {
            data[index] = [];
            angular.forEach(menu, function(item)
            {
              this.push(item.id);
            }, data[index]);
          });

          return $api$.put('menu', data);
        },
        publish: function()
        {
          return $api$.post('menu/publish');
        }
      };

      var itemPromise;

      var getIndex = function(list, id)
      {
        var n = list.length;
        while (n --) {
          if (list[n].id == id) {
            return n;
          }
        }

        return null;
      };

      var itemService = {
        get   : function(id)
        {
          return itemService.list().then(function(list)
          {
            var index = getIndex(list, id);
            return (index === null ? $q.reject(null) : list[index]);
          });
        },
        list  : function(forceUpdate)
        {
          if (forceUpdate || ! angular.isDefined(itemPromise)) {
            var defer = $q.defer();

            $api$.get('menu/item').success(function(list)
            {
              angular.copy(list, menuItems);
              defer.resolve(menuItems);
            });
            itemPromise = defer.promise;
          }

          return itemPromise;
        },
        add   : function(data)
        {
          return $api$.post('menu/item', data).success(function(response)
          {
            menuItems.unshift(response);
          });
        },
        update: function(data)
        {
          var id = data.id;

          return $api$.put('menu/item', data).success(function()
          {
            itemService.get(id).then(function(item)
            {
              angular.copy(data, item);
            });
          });
        },
        remove: function(data)
        {
          return $api$.delete('menu/item/' + data.id);
        }
      };

      return {
        menus: menuService,
        items: itemService
      };
    }
  ]);
});