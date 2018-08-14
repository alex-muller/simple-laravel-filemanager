let mix = require('laravel-mix');
const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');
const svgo = require('svgo')

mix.js('src/resources/assets/js/main.js', 'public/muller/filemanager/js')
  .sass('src/resources/assets/scss/main.scss', 'public/muller/filemanager/css').sourceMaps();

mix.webpackConfig({
  plugins: [
    new SVGSpritemapPlugin({
      src: 'src/resources/assets/sprite-source/*.svg',
      filename: 'public/muller/filemanager/img/symbols.svg',
      svgo: {
        plugins: [
          {
            removeTitle: true,
            mergePaths: true,
            convertTransform: true,
            removeUselessStrokeAndFill: true,
            collapseGroups: true
          }
        ]
      }
    })
  ]
})
