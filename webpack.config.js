var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('web/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .addEntry('app', './assets/js/main.js')
    .addStyleEntry('global', './assets/scss/global.scss')
    .enableSassLoader()
    .autoProvidejQuery()
    .enableVersioning(false)  
;

module.exports = Encore.getWebpackConfig();