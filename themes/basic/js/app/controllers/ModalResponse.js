define(['angular', 'controller'], function(angular, Controllers)
{
  Controllers.controller('ModalResponse', [
    '$scope', '$modalInstance', '$response$', 'type', 'id',
    function($scope, $modalInstance, $response$, type, id)
    {
      var store = {};

      $scope.choice = {
        type: type,
        id  : id
      };

      $scope.responseList = null;

      $scope.$watch('choice.type', function(currentType)
      {
        if (+ currentType) {
          var typeId = $response$.type(currentType);
          $response$.list(typeId).then(function(list)
          {
            $scope.responseList = list;
            if (store[currentType]) {
              $scope.choice.id = store[currentType];
            }
          });
        }
      });

      $scope.chooseId = function(id)
      {
        store[$scope.choice.type] = id;
      };

      $scope.cancel = function()
      {
        $scope.$dismiss('cancel');
      };

      $scope.ok = function()
      {
        $modalInstance.close($scope.choice);
      };
    }
  ]);
});
