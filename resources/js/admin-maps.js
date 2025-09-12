import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

// ================================================================
// FUNGSI UNTUK PETA DI HALAMAN INDEX (DAFTAR LOKASI)
// ================================================================
function initIndexMap() {
    const mapElement = document.getElementById('map');
    const dataContainer = document.getElementById('location-data'); 

    if (mapElement && dataContainer) {
        const locations = JSON.parse(dataContainer.dataset.locations || '[]');
        const defaultLat = -6.2088;
        const defaultLng = 106.8456;

        // --- PERUBAHAN DI SINI ---
        // 1. Definisikan 3 mode peta
        const streets = L.tileLayer('https://mt0.google.com/vt/lyrs=m&hl=en&x={x}&y={y}&z={z}&s=Ga', { maxZoom: 19, attribution: '&copy; Google' });
        const dark = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19, attribution: '&copy; CARTO' });
        const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19, attribution: 'Tiles &copy; Esri' });

        // 2. Inisialisasi peta dengan mode default 'streets'
        const map = L.map('map', {
            center: [defaultLat, defaultLng],
            zoom: 10,
            layers: [streets] // Set mode jalan sebagai default
        });

        // 3. Tambahkan kontrol layer ke peta
        const baseMaps = {
            "Jalan": streets,
            "Gelap": dark,
            "Satelit": satellite
        };
        L.control.layers(baseMaps).addTo(map);
        // --- AKHIR PERUBAHAN ---

        if (locations.length > 0) {
            const bounds = [];
            locations.forEach(location => {
                const latLng = [location.latitude, location.longitude];
                
                L.circle(latLng, {
                    color: '#1E90FF',
                    fillColor: '#1E90FF',
                    fillOpacity: 0.3,
                    radius: location.radius
                }).addTo(map);

                L.marker(latLng).addTo(map)
                    .bindPopup(`<b>${location.name}</b><br>Radius: ${location.radius} meter.`);
                
                bounds.push(latLng);
            });

            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [50, 50] });
            }
        }
    }
}


// ================================================================
// FUNGSI UNTUK PETA DI HALAMAN CREATE & EDIT (FORM)
// ================================================================
function initFormMap() {
    const mapElement = document.getElementById('map');
    if (!mapElement) return;

    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const radiusInput = document.getElementById('radius');
    
    let lat = parseFloat(latInput.value);
    let lng = parseFloat(lngInput.value);
    let radius = parseInt(radiusInput.value);

    // --- PERUBAHAN DI SINI ---
    // 1. Definisikan 3 mode peta
    const streets = L.tileLayer('https://mt0.google.com/vt/lyrs=m&hl=en&x={x}&y={y}&z={z}&s=Ga', { maxZoom: 19, attribution: '&copy; Google' });
    const dark = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19, attribution: '&copy; CARTO' });
    const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19, attribution: 'Tiles &copy; Esri' });

    // 2. Inisialisasi peta dengan mode default 'streets'
    const map = L.map('map', {
        center: [lat, lng],
        zoom: 15,
        layers: [streets] // Set mode jalan sebagai default
    });

    // 3. Tambahkan kontrol layer ke peta
    const baseMaps = {
        "Jalan": streets,
        "Gelap": dark,
        "Satelit": satellite
    };
    L.control.layers(baseMaps).addTo(map);
    // --- AKHIR PERUBAHAN ---

    let marker = L.marker([lat, lng], { draggable: true }).addTo(map);
    let geofenceCircle = L.circle([lat, lng], {
        color: '#1E90FF', fillColor: '#1E90FF', fillOpacity: 0.3, radius: radius
    }).addTo(map);
    
    function updateInputs(latlng) {
        latInput.value = latlng.lat.toFixed(6);
        lngInput.value = latlng.lng.toFixed(6);
    }
    
    function updateMapElements(latlng) {
        marker.setLatLng(latlng);
        geofenceCircle.setLatLng(latlng);
    }

    map.on('click', e => { updateMapElements(e.latlng); updateInputs(e.latlng); });
    marker.on('dragend', e => { const latlng = e.target.getLatLng(); updateMapElements(latlng); updateInputs(latlng); });
    radiusInput.addEventListener('input', function(e) {
        const newRadius = parseInt(e.target.value);
        if (!isNaN(newRadius) && newRadius > 0) {
            geofenceCircle.setRadius(newRadius);
        }
    });
}


// ================================================================
// EVENT LISTENER UTAMA
// ================================================================
document.addEventListener('turbo:load', function () {
    if (document.getElementById('location-data')) initIndexMap();
    if (document.querySelector('form #map')) initFormMap();
});
if (document.readyState === 'interactive' || document.readyState === 'complete') {
    if (document.getElementById('location-data')) initIndexMap();
    if (document.querySelector('form #map')) initFormMap();
}