require.config({
  urlArgs: typeof BUILD_HASH === 'undefined' ? (+ new Date()).toString(36)
  : BUILD_HASH,
  config : {
    route: {
      baseUrl   : '/console',
      baseTplUrl: '/themes/basic/template'
    }
  },
  paths  : {
    route     : 'app/route',
    c         : 'app/controllers',
    s         : 'app/services',
    d         : 'app/directives',
    controller: 'app/modules/controller',
    service   : 'app/modules/service',
    directive : 'app/modules/directive',
    ngUiRouter: 'vendor/angular-ui-router.min',
    ngUi      : 'vendor/ui-bootstrap-tpls-0.10.0.min',
    angular   : 'vendor/angular.min',
    ptloginout: 'http://imgcache.qq.com/ptlogin/ac/v9/js/ptloginout'
  },
  shim   : {
    angular   : {
      exports: 'angular'
    },
    ngUiRouter: {
      deps: ['angular']
    },
    ngUi      : {
      deps: ['angular']
    },
    ptloginout: {
      exports: 'pt_logout'
    }
  }
});

require(['app/app']);