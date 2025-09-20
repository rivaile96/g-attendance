import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

// Mengatasi masalah ikon default Leaflet yang rusak di build tools seperti Vite
import iconRetinaUrl from 'leaflet/dist/images/marker-icon-2x.png';
import iconUrl from 'leaflet/dist/images/marker-icon.png';
import shadowUrl from 'leaflet/dist/images/marker-shadow.png';

L.Icon.Default.mergeOptions({
    iconRetinaUrl,
    iconUrl,
    shadowUrl,
});


// ================================================================
// FUNGSI UNTUK PETA DI HALAMAN INDEX (DAFTAR LOKASI)
// ================================================================
function initIndexMap() {
    const mapElement = document.getElementById('map');
    const dataContainer = document.getElementById('location-data');

    if (!mapElement || !dataContainer) return;

    const locations = JSON.parse(dataContainer.dataset.locations || '[]');
    const defaultLat = -6.2088; // Default: Jakarta
    const defaultLng = 106.8456;

    const streets = L.tileLayer('https://mt0.google.com/vt/lyrs=m&hl=en&x={x}&y={y}&z={z}&s=Ga', { maxZoom: 19, attribution: '&copy; Google' });
    const dark = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19, attribution: '&copy; CARTO' });
    const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19, attribution: 'Tiles &copy; Esri' });

    const map = L.map('map', {
        center: [defaultLat, defaultLng],
        zoom: 10,
        layers: [streets]
    });

    const baseMaps = {
        "Jalan": streets,
        "Gelap": dark,
        "Satelit": satellite
    };
    L.control.layers(baseMaps).addTo(map);

    if (locations.length > 0) {
        const bounds = [];
        locations.forEach(location => {
            const latLng = [location.latitude, location.longitude];

            L.circle(latLng, {
                color: '#1e40af',
                fillColor: '#3b82f6',
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


// ================================================================
// FUNGSI UNTUK PETA DI HALAMAN CREATE & EDIT (FORM)
// ================================================================
function initFormMap() {
    const mapElement = document.getElementById('map');
    if (!mapElement) return;

    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const radiusInput = document.getElementById('radius');
    const findMeBtn = document.getElementById('find-me-btn');

    let lat = parseFloat(latInput.value) || -6.2088;
    let lng = parseFloat(lngInput.value) || 106.8456;
    let radius = parseInt(radiusInput.value) || 100;

    const streets = L.tileLayer('https://mt0.google.com/vt/lyrs=m&hl=en&x={x}&y={y}&z={z}&s=Ga', { maxZoom: 19, attribution: '&copy; Google' });
    const dark = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19, attribution: '&copy; CARTO' });
    const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19, attribution: 'Tiles &copy; Esri' });

    const map = L.map('map', {
        center: [lat, lng],
        zoom: 15,
        layers: [streets]
    });

    const baseMaps = {
        "Jalan": streets,
        "Gelap": dark,
        "Satelit": satellite
    };
    L.control.layers(baseMaps).addTo(map);

    let marker = L.marker([lat, lng], { draggable: true }).addTo(map);
    let geofenceCircle = L.circle([lat, lng], {
        color: '#1e40af',
        fillColor: '#3b82f6',
        fillOpacity: 0.3,
        radius: radius
    }).addTo(map);

    function updateInputs(latlng) {
        latInput.value = latlng.lat.toFixed(6);
        lngInput.value = latlng.lng.toFixed(6);
    }

    function updateMapElements(latlng) {
        marker.setLatLng(latlng);
        geofenceCircle.setLatLng(latlng);
    }

    // --- LOGIKA UNTUK FITUR BARU "TEMUKAN LOKASI SAYA" ---
    if (findMeBtn) {
        findMeBtn.addEventListener('click', () => {
            if (!navigator.geolocation) {
                alert('Browser Anda tidak mendukung Geolocation.');
                return;
            }

            findMeBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mencari...';
            findMeBtn.disabled = true;

            navigator.geolocation.getCurrentPosition((position) => {
                const newLatLng = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                map.setView(newLatLng, 17); // Center peta ke lokasi baru
                updateMapElements(newLatLng);
                updateInputs(newLatLng);

                findMeBtn.innerHTML = '<i class="fas fa-crosshairs mr-2"></i> Temukan Lokasi Saya';
                findMeBtn.disabled = false;

            }, () => {
                alert('Gagal mendapatkan lokasi. Pastikan izin lokasi sudah diberikan untuk situs ini.');
                findMeBtn.innerHTML = '<i class="fas fa-crosshairs mr-2"></i> Temukan Lokasi Saya';
                findMeBtn.disabled = false;
            });
        });
    }

    // Event listener yang sudah ada
    map.on('click', e => {
        updateMapElements(e.latlng);
        updateInputs(e.latlng);
    });
    marker.on('dragend', e => {
        const latlng = e.target.getLatLng();
        updateMapElements(latlng);
        updateInputs(latlng);
    });
    radiusInput.addEventListener('input', function(e) {
        const newRadius = parseInt(e.target.value);
        if (!isNaN(newRadius) && newRadius > 0) {
            geofenceCircle.setRadius(newRadius);
        }
    });
}


// ================================================================
// EVENT LISTENER UTAMA (LEBIH SIMPLE & MODERN)
// ================================================================
function runScripts() {
    if (document.getElementById('location-data')) {
        initIndexMap();
    }
    if (document.querySelector('form #map')) {
        initFormMap();
    }
}

// Jalankan script saat halaman pertama kali dimuat
document.addEventListener('DOMContentLoaded', runScripts);

// Jalankan script setiap kali Swup selesai memuat halaman baru
document.addEventListener('swup:contentReplaced', runScripts);
