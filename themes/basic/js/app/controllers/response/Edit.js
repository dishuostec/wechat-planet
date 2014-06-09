define(['angular', 'controller'], function(angular, Controllers)
{
  Controllers.controller('ResponseEdit', [
    '$scope', '$modalInstance', '$stateParams', '$response$', '$cacheFactory',
    function($scope, $modalInstance, $stateParams, $response$, $cacheFactory)
    {
      var id = $stateParams.id;
      var type = $stateParams.type;
      var cacheId = 'response.' + type;
      var cache = $cacheFactory.get(cacheId) || $cacheFactory(cacheId);
      var is_create = id === 'add';

      $scope.op = (is_create ? '新建' : '修改');
      $scope.response = cache.get(id);

      if (! angular.isDefined($scope.response)) {
        $response$.get(type, id).then(function(response)
        {
          $scope.response = angular.copy(response);
        }, function()
        {
          $scope.response = {};
        });
      }

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