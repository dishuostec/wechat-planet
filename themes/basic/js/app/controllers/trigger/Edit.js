define(['angular', 'controller', 's/modal'], function(angular, Controllers)
{
  Controllers.controller('TriggerEdit', [
    '$scope', '$modalInstance', '$stateParams', '$trigger$', '$response$',
    '$cacheFactory', '$modal$', '$q',
    function($scope, $modalInstance, $stateParams, $trigger$, $response$,
             $cacheFactory, $modal$, $q)
    {
      var id = $stateParams.id;
      var type = $stateParams.type;
      var cacheId = 'trigger.' + type;
      var cache = $cacheFactory.get(cacheId) || $cacheFactory(cacheId);
      var is_create = id === 'add';

      var getFromCache = function(id)
      {
        var data = cache.get(id);
        return angular.isDefined(data) ? data : (is_create ? {} : $q.reject());
      };

      var getFromService = function()
      {
        return $trigger$.get(type, id);
      };

      $q.when(getFromCache())

      .then(null, getFromService)

      .then(function(data)
      {
        $scope.trigger = data;
      }, function()
      {
        $scope.$dismiss('not found cancel');
      });

      $scope.op = (is_create ? '新建' : '修改');

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