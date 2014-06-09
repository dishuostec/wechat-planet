define(['angular', 'controller'], function(angular, Controllers)
{
  Controllers.controller('ResponseEdit', [
    '$scope', '$modalInstance', '$stateParams', '$response$', '$cacheFactory',
    '$q',
    function($scope, $modalInstance, $stateParams, $response$, $cacheFactory,
             $q)
    {
      var id = $stateParams.id;
      var type = $stateParams.type;
      var cacheId = 'response.' + type;
      var cache = $cacheFactory.get(cacheId) || $cacheFactory(cacheId);
      var is_create = id === 'add';

      var getFromCache = function(id)
      {
        var data = cache.get(id);
        return angular.isDefined(data) ? data : (is_create ? {} : $q.reject());
      };

      var getFromService = function()
      {
        return $response$.get(type, id);
      };

      $q.when(getFromCache())

      .then(null, getFromService)

      .then(function(data)
      {
        $scope.response = data;
      }, function()
      {
        $scope.$dismiss('not found cancel');
      });

      $scope.op = (is_create ? '新建' : '修改');
      $scope.response = cache.get(id);

      $scope.cancel = function()
      {
        $scope.$dismiss('cancel');
      };

      $scope.ok = function()
      {
        $response$[is_create ? 'add' : 'update'](type,
        $scope.response).then(function()
        {
          cache.remove(id);
        });

        $modalInstance.close();
      };

      $scope.remove = function()
      {
        $response$.remove(type, $scope.response).finally(function()
        {
          $scope.$dismiss('removed cancel');
        });
      };

      $modalInstance.result.then(null, function()
      {
        cache.put(id, $scope.response);
      });
    }
  ]);
});