require('dotenv').config();

const path = require('path')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')

const heartdir = process.env.DIR_HEART
const pluginsdir = process.env.DIR_PLUGINS

module.exports = env => { 
                                                    
  const input = Object.keys(env)[2] || ''
  const params = input.split('-')

  /**
  * type -> block, ui, metabox, etc (bc externals)
  * so/ur/ce -> where in src
  * DEST_DIR(plugin/theme) -> system destination type dir
  * (poeticsoft-heart-)NAME -> name destination 
  * des/ti/nation -> detination of compilation js & css
  */  

  const type = params[0]
  const source = params[1]
  const dest_public = params[2]
  const dest_dir = process.env[params[2]]
  const dest_name = `poeticsoft-heart-${params[3]}`
  const output = params[4]

  const mode = 'dev'
  const watch = 'si'

  const destdir = path.join(dest_dir, dest_name, output)
  const public = `/wp-content/${dest_public}/${dest_name}`
  
  let entry = {}
  let externals = {}  

  const wpblockexternals = {
    '@wordpress/element': 'wp.element',
    '@wordpress/i18n': 'wp.i18n',
    '@wordpress/blocks': 'wp.blocks'
  }
  const wpcompexternals = {          
    react: 'wp.element',
    'react-dom': 'wp.element'
  }

  switch (type) {

    case 'block':
      
      paths.output = destdir  + '/blocks/' + name + '/build'

      entry = {
        edit: './src/blocks/' + name + '/edit.js',
        view: './src/blocks/' + name + '/view.js'
      }

      externals = wpblockexternals

      break;

    case 'ui':
      
      paths.output = destdir + '/ui/' + name 

      entry = {
        main: './src/ui/' + name + '/main.js'
      }

      if(
        name == 'prompteditor'
      ) {

        externals = wpcompexternals
      }

      break;

    case 'metabox':
      
      paths.output = destdir  + '/ui/metabox/' + name

      entry = {
        main: './src/metabox/' + name + '/main.js'
      }
      
      externals = wpcompexternals
      
      break;

    default:

      break
  }

  const config = {
    context: __dirname,
    stats: 'minimal',
    watch: watch == 'si',
    name: 'minimal',
    entry: entry,
    output: {
      path: paths.output,
      publicPath: paths.public,
      filename: '[name].js'
    },
    mode: mode == 'prod' ? 'production' : 'development',
    devtool: mode == 'prod' ? false : 'source-map',
    module: {
      rules: [
        {
          test: /\.jsx?$/,
          exclude: /node_modules/,
          use: [          
            { 
              loader: 'babel-loader',
              options: {
                presets: [
                  '@babel/preset-env',
                  '@babel/preset-react'
                ]
              }
            }
          ]
        },
        {
          test: /\.s[ac]ss$/i,
          exclude: /node_modules/,
          use: [
            { 
              loader: MiniCssExtractPlugin.loader
            },
            {
              loader: 'css-loader'
            },
            {
              loader: 'sass-loader'
            }
          ]
        },
        {
          test: /\.css$/,
          include: /node_modules/,
          use: [
            { 
              loader: MiniCssExtractPlugin.loader
            },
            'style-loader',
            'css-loader'
          ]
        },
        // Assets
        {
          test: /\.(jpg|jpeg|png|gif|svg|woff|ttf|eot|mp3|woff|woff2|webm|mp4)$/,
          type: 'asset/resource',
          generator: {
            emit: false,
            filename: content => { 

              return content.filename.replace(themename, '')
            }
          }
        }
      ]
    },
    plugins: [
      new MiniCssExtractPlugin({
        filename: paths.cssfilename
      })
    ],
    resolve: {
      extensions: ['.js'],
      alias: {
        assets: path.resolve(destdir + '/assets'),        
        common: path.join(__dirname, 'src', 'common'),          
        blocks: path.join(__dirname, themename, 'block')
      }
    },
    externals: externals
  }

  return config
}