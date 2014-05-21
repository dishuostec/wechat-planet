define(['controller'], function(Controllers)
{
	Controllers.controller('Alert', [
		'$scope', '$http', '$alert$', '$rootScope',
		function($scope, $http, $alert$, $rootScope)
		{
			$rootScope.$on('Alert', function(e, list)
			{
				$scope.alerts = list;
			});

			$scope.remove = $alert$.remove;
		}
	])
});
