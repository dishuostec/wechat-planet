define(['angular', 'service', 's/api'], function(angular, Service)
{
  Service.factory('$auth$', [
    '$rootScope', '$api$', '$q', function($rootScope, $api$, $q)
    {
      var auth = {};
      var user;
      var accounts;
      var currentAccount;
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

      var dataProvider = {
        getUser          : function()
        {
          return user;
        },
        getAccounts      : function()
        {
          return accounts;
        },
        getCurrentAccount: function()
        {
          return currentAccount;
        }
      };

      var checkState = function(force)
      {
        if (! checked || force) {
          console.log('check');
          var defer = $q.defer();
          checked = defer.promise;

          $api$.post('auth').success(function(json)
          {
            auth = json;

            user = angular.copy(json);
            accounts = angular.copy(user.accounts);
            delete user.accounts;

            angular.forEach(accounts, function(account, key)
            {
              if (account.isCurrent) {
                currentAccount = accounts[key];
              }
            });

            console.info('[s]auth', user, accounts, currentAccount);

            defer.resolve(dataProvider);
          }).error(function(data)
          {
            defer.reject();
          });
        }

        return checked;
      };

      return {
        checkState: checkState
      };
    }
  ]);
});