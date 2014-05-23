define(['angular', 'controller', 's/auth'], function(angular, Controllers)
{
  Controllers.controller('Account', [
    '$scope', '$rootScope', '$auth$', '$api$', '$stateParams', '$q',
    function($scope, $rootScope, $auth$, $api$, $stateParams, $q)
    {
      var uri = 'account/' + $stateParams.accountId;

      $scope.ready = false;

      $scope.busying = false;
      $scope.name = null;
      $scope.account = null;

      $scope.save = function()
      {
        $scope.busying = true;

        $api$.put(uri, $scope.account).finally(function()
        {
          $scope.busying = false;
          refresh().then(function()
          {
            // 更新菜单数据
            $rootScope.$emit('account.changed', $scope.account);
          });
        });
      };

      var refresh = function()
      {
        var deferred = $q.defer();

        $api$.get(uri).success(function(account)
        {
          $scope.account = account;
          if ($scope.account.type == 0) {
            $scope.account.type = null;
          }
          $scope.name = account.name;

          deferred.resolve();
        }).error(function(data)
        {
          deferred.reject(data);
        });

        return deferred.promise;
      };

      refresh().then(function()
      {
        $scope.ready = true;
      });
    }
  ]);
});
