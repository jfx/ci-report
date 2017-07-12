var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('web/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .autoProvidejQuery()
    .enableSassLoader()
    .enableVersioning(false)
    .addStyleEntry('css/app', ['./assets/scss/app.scss'])
;

module.exports = Encore.getWebpackConfig();