module.exports = {
  entry: './src/entry',
  output: {
    path: './static',
    filename: 'bundle.js'
  },
  module: {
    loaders: [
      { test: /\.css$/, loader: "style!css" }
    ]
  }
}
