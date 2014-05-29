define(['angular', 'controller'], function(angular, Controllers)
{
  Controllers.controller('TriggerEdit', [
    '$scope', '$modalInstance', '$stateParams', '$trigger$', '$response$',
    '$cacheFactory',
    function($scope, $modalInstance, $stateParams, $trigger$, $response$,
             $cacheFactory)
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

      $scope.responseList = null;

      $scope.$watch('trigger.response_type', function(currentType)
      {
        if (currentType) {
          var type = $response$.type(currentType);
          $response$.list(type).then(function(list)
          {
            $scope.responseList = list;
          });
        }
      });

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

      $modalInstance.result.finally(function()
      {
        cache.put(id, $scope.trigger);
      });
    }
  ]);
});