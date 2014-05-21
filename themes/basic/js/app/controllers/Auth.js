define(['angular', 'controller', 'd/postMessage', 's/auth', 's/alert'],
function(angular, Controllers)
{
  Controllers.controller('Auth', [
    '$scope', '$rootScope', '$auth$', '$location', '$sce', '$state', '$alert$',
    function($scope, $rootScope, $auth$, $location, $sce, $state, $alert$)
    {
      $alert$.clear();

      var trustOrigin = 'http://xui.ptlogin2.qq.com';

      $scope.isGuest = false;
      $scope.style = {
        margin: '0 auto',
        width : '600px',
        height: '400px'
      };

      var origin = $location.protocol() + '://' + $location.host();
      if ($location.port() !== 80) {
        origin += ':' + $location.port();
      }

      $scope.loginSrc = $sce.trustAsResourceUrl(trustOrigin +
      '/cgi-bin/xlogin?appid=5000701&style=22&s_url=' + origin +
      $state.href('welcome'));

      $rootScope.$on('$postMessage.receive', function(e, message)
      {
//        console.log(message);

        if (message.origin !== trustOrigin) {
          return;
        }

        var data = angular.fromJson(message.data);
//        console.log('message', data);
        switch (data.action) {
          case 'close':
            //                ptlogin2_onClose();
            break;

          case 'resize':
            $scope.$apply(function()
            {
              $scope.style.width = data.width + 'px';
              $scope.style.height = data.height + 'px';
            });
            break;

          default:
        }
      });

      $auth$.checkState().then(function(user)
      {
        $state.go('welcome');
      }, function()
      {
        $scope.isGuest = true;
      });
    }
  ]);
});
