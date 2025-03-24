module.exports = {
    presets: [
      '@babel/preset-env',  // Ce preset permet de transpiler le code moderne JavaScript
      '@babel/preset-react' // Si tu utilises React, sinon tu peux l'ignorer
    ],
    plugins: [
      '@babel/plugin-transform-runtime'
    ]
  };