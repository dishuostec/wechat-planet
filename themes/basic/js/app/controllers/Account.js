define(['angular', 'controller', 's/auth'], function(angular, Controllers)
{
  Controllers.controller('Account', [
    '$scope', '$rootScope', '$auth$', '$api$', '$stateParams',
    function($scope, $rootScope, $auth$, $api$, $stateParams)
    {
      var uri = 'account/' + $stateParams.accountId;

      $scope.ready = false;

      $scope.busying = false;
      $scope.name = null;
      $scope.account = null;

      $scope.save = function()
      {
        $scope.busying = true;

        $scope.account.id='yyyyyyyyyyy';

        $api$.put(uri, $scope.account).finally(function()
        {
          $scope.busying = false;
        });
      };

      $api$.get(uri).success(function(account)
      {
        console.log(account);
        $scope.ready = true;
        $scope.account = account;
        if($scope.account.type == 0)
        {
          $scope.account.type = null;
        }
        $scope.name = account.name;
      });
    }
  ]);
});
