define(['module'], function(module)
{
  var config = module.config();
  config.baseApi = config.baseUrl + '/api';

  return module.config();
});
