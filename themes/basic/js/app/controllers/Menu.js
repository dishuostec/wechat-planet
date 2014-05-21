define(['controller', 's/auth'], function(Controllers)
{
  Controllers.controller('Menu', [
    '$scope', '$rootScope', '$auth$', '$api$', '$q',
    function($scope, $rootScope, $auth$, $api$, $q)
    {
      $scope.isCollapsed = true;
      $scope.menu = null;
      $scope.user = null;

      $scope.logout = function()
      {
        console.log('logout');
        require(['ptloginout'], function(ptloginout)
        {
          ptloginout.logout(function()
          {
            console.log('ptloginout callback');
            $auth$.checkState(true);
          });
        })
      };

      $rootScope.$on('auth.login', function(e, user)
      {
        console.log('logged in', user);
        $scope.user = user;
        $api$.get('auth').success(function(menu)
        {
          $scope.menu = menu;
        });
      });

      $rootScope.$on('auth.logout', function(e, user)
      {
        console.log('logged out', user);
        $scope.menu = null;
        $scope.user = null;
      });

      $auth$.checkState();
    }
  ]);
});
