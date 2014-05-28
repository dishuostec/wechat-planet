define(['angular', 'controller', 's/auth'], function(angular, Controllers)
{
  Controllers.controller('Menu', [
    '$scope', '$rootScope', '$auth$', '$api$', '$state', '$window',
    function($scope, $rootScope, $auth$, $api$, $state, $window)
    {
      $scope.ready = false;

      $scope.isCollapsed = true;
      $scope.menu = null;
      $scope.user = null;
      $scope.currentAccount = null;
      $scope.accounts = null;

      var logoutCallback = function()
      {
        console.log('logged out');
        $window.location.reload();
      };

      $rootScope.$on('auth.logout', logoutCallback);

      $scope.logout = function()
      {
        console.log('logout');
        require(['ptloginout'], function(ptloginout)
        {
          ptloginout.logout(logoutCallback);
        })
      };

      $scope.changeAccount = function(account_id)
      {
        if (account_id === $scope.currentAccount.id) {
          return false;
        }

        $api$.post('account/current/' + account_id).success(function()
        {
          $window.location.reload();
        });
      };

      $auth$.checkState().then(function(auth)
      {
        $scope.ready = true;

        $scope.currentAccount = auth.getCurrentAccount();
        $scope.user = auth.getUser();
        $scope.accounts = auth.getAccounts();

        $api$.get('auth/menu').success(function(menu)
        {
          $scope.menu = menu;
        });
      }, function()
      {
        $scope.ready = true;
      });
    }
  ]);
});
