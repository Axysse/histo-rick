import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import { map } from './modules/map.js';
import './modules/nav.js';
// import './modules/sliders.js';

document.addEventListener('DOMContentLoaded', () => {
    map(); // Ensure your map initialization function is called
    console.log('map loaded'); // Test message
});
