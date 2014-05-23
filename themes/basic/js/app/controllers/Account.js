define(['angular', 'controller', 's/auth'], function(angular, Controllers)
{
  Controllers.controller('Account', [
    '$scope', '$rootScope', '$auth$', '$api$', '$stateParams', '$q',
    function($scope, $rootScope, $auth$, $api$, $stateParams, $q)
    {
      var api = function(resource)
      {
        if (resource) {
          resource += '/';
        } else {
          resource = '';
        }
        return 'account/' + resource + $stateParams.accountId;
      };

      $scope.ready = false;

      $scope.busying = false;
      $scope.name = null;
      $scope.account = null;

      $scope.save = function()
      {
        $scope.busying = true;

        $api$.put(api(), $scope.account).finally(function()
        {
          $scope.busying = false;
          refresh().then(function()
          {
            // 更新菜单数据
            $rootScope.$emit('account.changed', $scope.account);
          });
        });
      };

      $scope.changeUrl = function()
      {
        $api$.post(api('url')).success(function(json)
        {
          $scope.account.url = json.data;
        });
      };

      $scope.changeToken = function()
      {
        $api$.post(api('token')).success(function(json)
        {
          $scope.account.token = json.data;
        });
      };

      var refresh = function()
      {
        var deferred = $q.defer();

        $api$.post(api('detail')).success(function(account)
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
