require.config({
  urlArgs: typeof BUILD_HASH === 'undefined' ? (+ new Date()).toString(36)
  : BUILD_HASH,
  config : {
    config: {
      baseUrl   : '/console',
      baseTplUrl: '/themes/basic/template'
    }
  },
  paths  : {
    config        : 'app/config',
    route         : 'app/route',
    c             : 'app/controllers',
    s             : 'app/services',
    d             : 'app/directives',
    h             : 'app/helper',
    controller    : 'app/modules/controller',
    service       : 'app/modules/service',
    directive     : 'app/modules/directive',
    ngUiRouter    : 'vendor/angular-ui-router.min',
    ngUiSortable  : 'vendor/sortable.min',
    ngUi          : 'vendor/ui-bootstrap-tpls.min',
    jQuery        : 'vendor/jquery-1.10.2',
    jQueryUi      : 'vendor/jquery-ui-1.10.4.custom.min',
    angular       : 'vendor/angular.min',
    angularAnimate: 'vendor/angular-animate.min',
    ptloginout    : 'http://imgcache.qq.com/ptlogin/ac/v9/js/ptloginout'
  },
  shim   : {
    angular       : {
      deps   : ['jQuery'],
      exports: 'angular'
    },
    angularAnimate: {
      deps: ['angular']
    },
    ngUiRouter    : {
      deps: ['angular']
    },
    jQuery        : {
      exports: 'jQuery'
    },
    jQueryUi      : {
      deps: ['jQuery']
    },
    ngUiSortable  : {
      deps: ['jQuery', 'jQueryUi']
    },
    ngUi          : {
      deps: ['angular']
    },
    ptloginout    : {
      exports: 'pt_logout'
    }
  }
});

require(['app/app']);