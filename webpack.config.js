const path = require('path');

module.exports = {
  // Autres configurations de Webpack

  resolve: {
    alias: {
      'stimulus': path.resolve(__dirname, 'node_modules/stimulus')
    }
  }
};

const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
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
;

module.exports = Encore.getWebpackConfig();
