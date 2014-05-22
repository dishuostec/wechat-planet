define(['service', 'config', 's/alert'], function(Service, config)
{
  Service.factory('$apiHttpInterceptor$', [
    '$q', '$alert$', '$injector', function($q, $alert$, $injector)
    {
      var strMatch = function(match)
      {
        var len = match.length;
        return function(string)
        {
          return string.length > len && string.slice(0, len) === match;
        };
      };

      var isApi = strMatch(config.baseApi);
      var isTpl = strMatch(config.baseTplUrl);

      var apiSuccess = function(response)
      {
        var $state = $injector.get('$state');

        if ($state.is('login')) {
          return;
        }

        switch (response.status) {
          case 201:
            $alert$.success({
              message: '创建成功'
            }, 2000);
            break;
          case 204:
            $alert$.success({
              message: '成功'
            }, 2000);
            break;
        }
        console.info('[s]api success', response);
      };

      var apiError = function(rejection)
      {
        var $state = $injector.get('$state');

        if ($state.is('login')) {
          return;
        }

        switch (rejection.status) {
          case 400: // 错误消息
            $alert$.error(rejection.data, 5000);
            break;
          case 401: // 未登录
            $state.go('login');
            break;
          case 403:
            $alert$.error({message: '没有权限'}, 5000);
            break;
          case 404:
            $alert$.error({message: '请求的资源不存在'}, 5000);
            break;
          case 406: // 提交参数错误
            angular.forEach(rejection.data, function(errors)
            {
              angular.forEach(errors, function(error)
              {
                $alert$.error(error);
              });
            });
            break;
          case 429: // 服务器错误
            $alert$.error({message: '达到API请求次数限制'});
            break;
          case 500: // 服务器错误
            $alert$.warn({message: '服务器错误'});
            break;
          case 503: // 服务器不可用
            $alert$.warn({message: '服务器暂不可用'});
            break;
          default :
            console.log(rejection);
        }
      };

      var tplError = function(rejection)
      {
        $alert$.warning({
          message: '加载模版失败: {tpl}',
          params : {
            tpl: rejection.config.url.slice(config.baseTplUrl.length)
          }
        });
      };

      return {
        response     : function(response)
        {
          if (isApi(response.config.url)) {
            apiSuccess(response);
          }
          return response;
        },
        responseError: function(rejection)
        {
          var url = rejection.config.url;
          if (isApi(url)) {
            apiError(rejection);
          } else if (isTpl(url)) {
            tplError(rejection);
          }

          return $q.reject(rejection);
        }
      }
    }
  ]);
});