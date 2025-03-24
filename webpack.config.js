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
  .configureBabel((babelConfig) => {
    babelConfig.presets = ['@babel/preset-env'];  // Utilisation de preset-env pour transpiler le JS moderne
    babelConfig.plugins = [
      '@babel/plugin-transform-runtime'
    ];
    babelConfig.sourceType = 'module';  // Assure que Babel traite les import/export correctement
  })
  .addAliases({
    'stimulus': path.resolve(__dirname, 'node_modules/stimulus')
  });

module.exports = Encore.getWebpackConfig();