define(['angular', 'controller', 's/menu', 'c/Menu/Edit'],
function(angular, Controllers)
{
  Controllers.controller('Menu', [
    '$scope', '$menu$', function($scope, $menu$)
    {
      var originList = [];

      $scope.menus = [];
      $scope.list = [];

      var refreshList = function()
      {
        angular.copy(originList, $scope.list);
      };

      $scope.menuSortOptions = {
        helper     : 'clone',
        cursor     : 'move',
        placeholder: 'menu-item-move-placeholder',
        opacity    : 0.5,
        connectWith: '.menu-design'
      };

      $scope.listSortOptions = {
        helper     : 'clone',
        cursor     : 'move',
        placeholder: 'menu-item-add-placeholder',
        opacity    : 0.5,
        connectWith: '.menu-design',
        stop       : refreshList
      };

      $scope.removeMenu = function(menuIndex, itemIndex)
      {
        $scope.menus[menuIndex].splice(itemIndex, 1);
      };

      $scope.updateMenu = $menu$.menus.update;
      $scope.publishMenu = $menu$.menus.publish;

      $scope.$watch(function()
      {
        return originList.length;
      }, refreshList);

      $menu$.menus.get().then(function(data)
      {
        $scope.menus = data;
      });

      $menu$.items.list().then(function(list)
      {
        originList = list;
        $scope.list = list.slice();
      })
    }
  ]);
});