import Alpine from 'alpinejs';
import L from 'leaflet';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const mapEl = document.getElementById('property-map');

    if (!mapEl) {
        return;
    }

    const lat = parseFloat(mapEl.dataset.lat);
    const lng = parseFloat(mapEl.dataset.lng);

    if (Number.isNaN(lat) || Number.isNaN(lng)) {
        return;
    }

    const map = L.map(mapEl).setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    L.marker([lat, lng]).addTo(map);
});
