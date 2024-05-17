import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import './styles/header.css';
import './styles/login.css';
import './styles/accueil.css'
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';


document.addEventListener('DOMContentLoaded', () => {
    var map = L.map('map').setView([51.505, -0.09], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    L.marker([51.5, -0.09]).addTo(map)
        .bindPopup('A pretty CSS3 popup.<br> Easily customizable.')
        .openPopup();
});

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
