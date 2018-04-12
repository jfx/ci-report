var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .addEntry('app', './assets/js/main.js')
    .addStyleEntry('global', './assets/scss/global.scss')
    .enableSassLoader()
    .autoProvidejQuery()
    .enableVersioning(false) 
;

module.exports = Encore.getWebpackConfig();
