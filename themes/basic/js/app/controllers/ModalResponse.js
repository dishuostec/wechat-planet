define(['angular', 'controller'], function(angular, Controllers)
{
  Controllers.controller('ModalResponse', [
    '$scope', '$modalInstance', '$response$', 'type', 'id',
    function($scope, $modalInstance, $response$, type, id)
    {
      $scope.response = {
        item: function()
        {
          return $response$.get(type, id);
        },
        type:type,
        id:id
      };

      $scope.responseList = null;

      $scope.$watch('response.type', function(currentType)
      {
        if (currentType) {
          var typeId = $response$.type(currentType);
          $response$.list(typeId).then(function(list)
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
        $modalInstance.close($scope.response);
      };
    }
  ]);
});
