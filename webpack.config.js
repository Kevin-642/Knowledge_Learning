const path = require('path');
const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .addEntry('app', './assets/app.js')
  .enableSassLoader()
  .enableVersioning()
  .enableSourceMaps(!Encore.isProduction())
  .enableSingleRuntimeChunk()
  .configureBabel(null, (config) => {
    config.presets.push('@babel/preset-env');
    config.sourceType = 'unambiguous'; 
})
  .addAliases({
    'stimulus': path.resolve(__dirname, 'node_modules/stimulus')
  });

module.exports = Encore.getWebpackConfig();