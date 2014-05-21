define(['angular', 'service', 's/api'], function(angular, Service)
{
  Service.factory('$auth$', [
    '$rootScope', '$api$', '$q', function($rootScope, $api$, $q)
    {
      var auth = {};
      var checked;

      $rootScope.$watch(function()
      {
        return auth.id || '';
      }, function(currentId, prevId)
      {
        if (currentId !== prevId) {
          $rootScope.$emit(currentId ? 'auth.login' : 'auth.logout', auth);
        }
      });

      var checkState = function(force)
      {
        if (! checked || force) {
          console.log('check');
          var defer = $q.defer();
          checked = defer.promise;

          $api$.post('auth').success(function(json)
          {
            auth = json;
            defer.resolve(auth);
          }).error(function(data)
          {
            defer.reject();
          });
        }

        return checked;
      };

      return {
        checkState: checkState,
        getUser   : function()
        {
          return auth;
        }
      };
    }
  ]);
});