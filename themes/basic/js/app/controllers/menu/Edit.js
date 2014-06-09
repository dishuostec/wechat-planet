define(['angular', 'controller', 's/modal'], function(angular, Controllers)
{
  Controllers.controller('MenuEdit', [
    '$scope', '$modalInstance', '$stateParams', '$menu$', '$cacheFactory',
    '$modal$','$q',
    function($scope, $modalInstance, $stateParams, $menu$, $cacheFactory,
             $modal$, $q)
    {
      var id = $stateParams.id;
      var cacheId = 'menu';
      var cache = $cacheFactory.get(cacheId) || $cacheFactory(cacheId);
      var is_create = id === 'add';

      var getFromCache = function(id)
      {
        var data = cache.get(id);
        return angular.isDefined(data) ? data : (is_create ? {} : $q.reject());
      };

      var getFromService = function()
      {
        return $menu$.items.get(id);
      };

      $q.when(getFromCache())

      .then(null, getFromService)

      .then(function(data)
      {
        $scope.menu = data;
      }, function()
      {
        $scope.$dismiss('not found cancel');
      });

      $scope.op = (is_create ? '新建' : '修改');
      $scope.menu = cache.get(id);

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

