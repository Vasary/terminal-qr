const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('../public/build/')
    .setPublicPath('/build')
    .addEntry('app', './app.js')
    .addEntry('terminal', './scripts/terminal/terminal.js')
    .addEntry('paymentStatus', './scripts/terminal/status.ts')
    .addEntry('payment', './scripts/payment/payment.page.ts')
    .enableStimulusBridge('./controllers.json')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    .enableSassLoader()
    .enableTypeScriptLoader()
    .enableIntegrityHashes(Encore.isProduction())
    .copyFiles({
        from: './images',
        to: 'images/[path][name].[ext]',
    })
;

module.exports = Encore.getWebpackConfig();
