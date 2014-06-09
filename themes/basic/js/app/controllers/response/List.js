define([
  'config', 'angular', 'controller', 's/auth', 's/response', 'c/response/Edit'
], function(config, angular, Controllers)
{
  Controllers.controller('ResponseList', [
    '$scope', '$stateParams', '$q', '$response$',
    function($scope, $stateParams, $q, $response$)
    {
      $scope.list = null;

      var type = $scope.type = $stateParams.type;

      $response$.list(type).then(function(list)
      {
        $scope.list = list;
      });
    }
  ]);
});
