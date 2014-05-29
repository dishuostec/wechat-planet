define([
  'config', 'angular', 'controller', 's/auth', 's/trigger', 'c/trigger/Edit'
], function(config, angular, Controllers)
{
  Controllers.controller('TriggerList', [
    '$scope', '$rootScope', '$auth$', '$api$', '$stateParams', '$q',
    '$trigger$', '$modal',
    function($scope, $rootScope, $auth$, $api$, $stateParams, $q, $trigger$,
             $modal)
    {
      $scope.list = null;

      var type = $scope.type = $stateParams.type;

      $scope.del = function(index)
      {
        $trigger$.remove(type, $scope.list[index]).then(function()
        {
          $scope.list.splice(index, 1);
        });
      };

      $trigger$.list(type).then(function(list)
      {
        $scope.list = list;
      });
    }
  ]);
});
