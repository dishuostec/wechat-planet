define(['jQuery', 'controller', 's/menu'], function($, Controllers)
{
  Controllers.controller('Menu', [
    '$scope', '$menu$', function($scope, $menu$)
    {
      $scope.menuSortOptions = {
        helper     : 'clone',
        cursor     : 'move',
        placeholder: 'menu-item-move-placeholder',
        opacity: 0.5,
        connectWith: '.menu-design'
      };

      $scope.listSortOptions = {
        helper     : 'clone',
        cursor     : 'move',
        placeholder: 'menu-item-add-placeholder',
        opacity: 0.5,
        connectWith: '.menu-design',
        stop       : function()
        {
          $scope.list = $scope.originList.slice();
        }
      };

      $scope.removeMenu = function(menuIndex, itemIndex)
      {
        $scope.menus[menuIndex].splice(itemIndex, 1);
      };

      //      $scope.moveMenu = function(menuIndex, itemIndex, isUp)
      //      {
      //        var menu = $scope.menus[menuIndex];
      //        if (isUp && itemIndex ===0 )
      //        {
      //
      //        }
      //        var item = $scope.menus[menuIndex].splice(itemIndex, 1);
      //        console.log('remove', menuIndex, itemIndex, isUp);
      //      };

      //      $scope.addMenu = function(index)
      //      {
      //        var i = Math.floor(Math.random() * $scope.items.length);
      //        $scope.menus[index].push($scope.items[i]);
      //      };

      $scope.updateMenu = $menu$.menus.update;
      $scope.publishMenu = $menu$.menus.publish;

      $menu$.menus.get().then(function(data)
      {
        $scope.menus = data;
      });

      $menu$.items.list().then(function(list)
      {
        $scope.originList = list;
        $scope.list = list.slice();
      })
    }
  ]);
});