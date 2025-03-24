const path = require('path');
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
  .enableBabelLoader()  // Ajout de cette ligne pour activer Babel
  .enableReactPreset()  // Si tu utilises React (si non, tu peux ignorer cette ligne)
  .configureBabel((babelConfig) => {
    babelConfig.presets = ['@babel/preset-env'];  // Utilisation du preset env pour transpiler le JS moderne
    babelConfig.plugins = [
      '@babel/plugin-transform-runtime'
    ];
  })
  .addAliases({
    'stimulus': path.resolve(__dirname, 'node_modules/stimulus')
  });

// Export final Webpack config
module.exports = Encore.getWebpackConfig();