define(['config', 'angular', 'service', 's/response', 'c/ModalResponse'],
function(config, angular, Service)
{
  Service.factory('$modal$', [
    '$rootScope', '$response$', '$q', '$modal',
    function($rootScope, $response$, $q, $modal)
    {
      return {
        response: function(type, id)
        {
          return $modal.open({
            templateUrl: config.baseTplUrl + '/response/modal/choose.html',
            controller : 'ModalResponse',
            resolve    : {
              type: function()
              {
                return type;
              },
              id  : function()
              {
                return id;
              }
            }
          });
        }
      };
    }
  ])
});