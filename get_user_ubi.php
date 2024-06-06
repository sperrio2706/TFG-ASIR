<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <script>
        L_NO_TOUCH = false;
        L_DISABLE_3D = false;
    </script>
    <style>
        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
    <style>
        #map {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.3/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/python-visualization/folium/folium/templates/leaflet.awesome.rotate.min.css" />
    <meta name="viewport" content="width=device-width,
        initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <style>
        #map {
            position: relative;
            width: 100.0%;
            height: 100.0%;
            left: 0.0%;
            top: 0.0%;
        }

        .leaflet-container {
            font-size: 1rem;
        }

        .locate-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
        }

        .fichar-btn {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
        }
    </style>
</head>

<body>
    <div class="folium-map" id="map"></div>
    <button class="locate-btn btn btn-primary">Obtener Ubicación</button>
    <form id="fichar-form" action="guardar_fichaje.php" method="POST" style="display: none;">
        <input type="hidden" name="latitude" id="latitude-input">
        <input type="hidden" name="longitude" id="longitude-input">
    </form>

    <script>
        var map = L.map("map", {
            center: [0, 0],
            zoom: 15
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Agregar un marcador predeterminado
        var marker = L.marker([0, 0]).addTo(map);

        // Función para actualizar la ubicación del marcador
        function updateMarker(lat, lon) {
            marker.setLatLng([lat, lon]);
            map.setView([lat, lon], 15);
            document.getElementById("latitude-input").value = lat;
            document.getElementById("longitude-input").value = lon;
        }

        // Función para obtener la ubicación actual del usuario
        function getLocation() {
            var ubicacion = "<?php echo isset($_POST['ubicacion']) ? $_POST['ubicacion'] : ''; ?>";

            if (ubicacion) {
                var coordenadas = ubicacion.split(",");
                var longitude = parseFloat(coordenadas[0]); // Longitud primero
                var latitude = parseFloat(coordenadas[1]); // Latitud luego

                if (!isNaN(latitude) && !isNaN(longitude)) {
                    updateMarker(latitude, longitude);
                } else {
                    alert("No se pudo obtener la ubicación.");
                }
            } else {
                alert("No se recibieron coordenadas de ubicación.");
            }
        }




        // Función para enviar la ubicación al servidor al hacer clic en el botón "Fichar"
        function sendLocationToPHP(latitude, longitude) {
            var formData = new FormData();
            formData.append('latitude', latitude);
            formData.append('longitude', longitude);

            fetch('guardar_fichaje.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    alert(data); // Muestra un mensaje de confirmación del fichaje
                })
                .catch(error => {
                    console.error('Error al enviar los datos al servidor PHP:', error);
                    alert('Error al enviar los datos al servidor PHP. Por favor, inténtalo de nuevo.');
                });
        }

        document.querySelector('.locate-btn').addEventListener('click', getLocation);
        document.querySelector('.fichar-btn').addEventListener('click', function() {

            // Enseña la ubicacion
            getLocation();

        });
    </script>
</body>

</html>