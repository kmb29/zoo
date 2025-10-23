const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('frontend', './assets/frontend/main.js')
    .cleanupOutputBeforeBuild()
    .copyFiles({
        from: './assets/images',
        to: 'images/[path][name].[ext]'
    })
    .enableVueLoader()
    .enableSingleRuntimeChunk()
;

module.exports = Encore.getWebpackConfig();
