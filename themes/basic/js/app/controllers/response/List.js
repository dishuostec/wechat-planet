define([
  'config', 'angular', 'controller', 's/auth', 's/response', 'c/response/Edit'
], function(config, angular, Controllers)
{
  Controllers.controller('ResponseList', [
    '$scope', '$rootScope', '$auth$', '$api$', '$stateParams', '$q',
    '$response$', '$modal',
    function($scope, $rootScope, $auth$, $api$, $stateParams, $q, $response$,
             $modal)
    {
      $scope.list = null;

      var type = $scope.type = $stateParams.type;

      //      $scope.add = function()
      //      {
      //        $modal.open({
      //          templateUrl: config.baseTplUrl + '/response/add.html',
      //          controller : 'ResponseAdd',
      //          size       : 'sm',
      //          keyboard   : false,
      //          backdrop   : 'static'
      //
      //        }).result.then(function(data)
      //        {
      //          $response$.add($scope.type, data).then(function(response)
      //          {
      //            $scope.list.push(response);
      //          });
      //        });
      //      };
      //
      //      $scope.edit = function(index)
      //      {
      //        console.log('edit', index);
      //      };
      //
      $scope.del = function(index)
      {
        $response$.remove(type, $scope.list[index]).then(function()
        {
          $scope.list.splice(index, 1);
        });
      };

      $response$.list(type).then(function(list)
      {
        $scope.list = list;
      });
    }
  ]);
});
