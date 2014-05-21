define(['angular', 'directive'], function(angular, Directive)
{
  Directive.directive('html', [
    '$window', '$rootScope', function($window, $rootScope)
    {
      return {
        restrict: 'E',
        link    : function()
        {
          angular.element($window).bind('message', function(event)
          {
            var message = {
              origin: event.origin
            };

            try {
              message.data = angular.fromJson(event.data);
            } catch (error) {
              message.data = event.data;
            }
            $rootScope.$emit('$postMessage.receive', message);
          });
        }
      }
    }
  ]);
});