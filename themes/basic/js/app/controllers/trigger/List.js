define([
  'config', 'angular', 'controller', 's/auth', 's/trigger', 'c/trigger/Edit'
], function(config, angular, Controllers)
{
  Controllers.controller('TriggerList', [
    '$scope', '$stateParams', '$trigger$',
    function($scope, $stateParams, $trigger$)
    {
      $scope.list = null;

      var type = $scope.type = $stateParams.type;

      $trigger$.list(type).then(function(list)
      {
        $scope.list = list;
      });
    }
  ]);
});
