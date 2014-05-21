define(['service', 's/alert'], function(Service)
{
  Service.factory('$apiHttpInterceptor$', [
    '$q', '$alert$', '$injector', function($q, $alert$, $injector)
    {
      return {
        responseError: function(rejection)
        {
          var $state = $injector.get('$state');

          if (! $state.is('login')) {
            switch (rejection.status) {
              case 400: // 错误消息
                $alert$.error(rejection.data);
                break;
              case 401: // 未登录
                $state.go('login');
                break;
              case 406: // 提交参数错误
                angular.forEach(rejection.data, function(errors)
                {
                  $alert$.error(errors);
                });
                break;
              case 500: // 服务器错误
                $alert$.error(rejection.data);
            }
          }

          return $q.reject(rejection);
        }
      }
    }
  ]);
});