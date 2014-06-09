define(['angular', 'controller', 's/modal'], function(angular, Controllers)
{
  Controllers.controller('MenuEdit', [
    '$scope', '$modalInstance', '$stateParams', '$menu$', '$cacheFactory',
    '$modal$',
    function($scope, $modalInstance, $stateParams, $menu$, $cacheFactory,
             $modal$)
    {
      var id = $stateParams.id;
      var cacheId = 'menu';
      var cache = $cacheFactory.get(cacheId) || $cacheFactory(cacheId);
      var is_create = id === 'add';

      $scope.op = (is_create ? '新建' : '修改');
      $scope.menu = cache.get(id);

      if (! angular.isDefined($scope.menu)) {
        $menu$.items.get(id).then(function(menu)
        {
          if (! + menu.type) {
            menu.type = null;
          }
          $scope.menu = angular.copy(menu);
        }, function()
        {
          $scope.menu = {};
        });
      }

      $scope.editResponse = function()
      {
        var modalInstance = $modal$.response($scope.menu.response_type,
        $scope.menu.response_id);

        modalInstance.result.then(function(data)
        {
          console.log('modal close', data);
          $scope.menu.response_type = data.type;
          $scope.menu.response_id = data.id;
        });
      };

      $scope.cancel = function()
      {
        $scope.$dismiss('cancel');
      };

      $scope.ok = function()
      {
        $menu$.items[is_create ? 'add' : 'update']($scope.menu).then(function()
        {
          cache.remove(id);
        });

        $modalInstance.close();
      };

      $scope.remove = function()
      {
        $menu$.items.remove($scope.menu).finally(function()
        {
          $scope.$dismiss('removed cancel');
        });
      };

      $modalInstance.result.then(null, function()
      {
        cache.put(id, $scope.menu);
      });
    }
  ]);
});

