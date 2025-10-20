const defaultConfig = require("@wordpress/scripts/config/webpack.config");

module.exports = {
	...defaultConfig,
	entry: {
		index: "./src/index.js",
		style: "./css/style.scss",
	},
	output: {
		...defaultConfig.output,
		path: __dirname + "/build",
		filename: "[name].js",
	},
};
