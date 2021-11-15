/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

require('../node_modules/semantic-ui/dist/semantic.min.css')
const jQuery = require('../node_modules/jquery/dist/jquery.js');

window.$ = window.jQuery = jQuery;
require('../node_modules/semantic-ui/dist/semantic.min.js')
require('./js/main.js');

// start the Stimulus application
// import './bootstrap.min.css';
