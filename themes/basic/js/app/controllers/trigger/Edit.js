define(['angular', 'controller', 's/modal'], function(angular, Controllers)
{
  Controllers.controller('TriggerEdit', [
    '$scope', '$modalInstance', '$stateParams', '$trigger$', '$response$',
    '$cacheFactory', '$modal$',
    function($scope, $modalInstance, $stateParams, $trigger$, $response$,
             $cacheFactory, $modal$)
    {
      var id = $stateParams.id;
      var type = $stateParams.type;
      var cacheId = 'trigger.' + type;
      var cache = $cacheFactory.get(cacheId) || $cacheFactory(cacheId);
      var is_create = id === 'add';

      $scope.op = (is_create ? '新建' : '修改');
      $scope.trigger = cache.get(id);

      if (! angular.isDefined($scope.trigger)) {
        $trigger$.get(type, id).then(function(trigger)
        {
          $scope.trigger = angular.copy(trigger);
        }, function()
        {
          $scope.trigger = {};
        });
      }

      $scope.editResponse = function()
      {
        var modalInstance = $modal$.response($scope.trigger.response_type,
        $scope.trigger.response_id);

        modalInstance.result.then(function(data)
        {
          console.log('modal close', arguments);
          $scope.trigger.response_type = data.type;
          $scope.trigger.response_id = data.id;
        });
      };

      $scope.cancel = function()
      {
        $scope.$dismiss('cancel');
      };

      $scope.ok = function()
      {
        $trigger$[is_create ? 'add' : 'update'](type,
        $scope.trigger).then(function()
        {
          cache.remove(id);
        });

        $modalInstance.close();
      };

      $scope.remove = function(index)
      {
        $trigger$.remove(type, $scope.trigger).then(function()
        {
          $scope.$dismiss('removed cancel');
        });
      };

      $modalInstance.result.then(null, function()
      {
        cache.put(id, $scope.trigger);
      });
    }
  ]);
});