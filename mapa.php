<?php

include 'conexao.php';

$sql = "SELECT * FROM denuncias";

$resultado = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>

<meta charset="UTF-8">
<title>Mapa de Denúncias</title>

<link rel="stylesheet" href="style.css">

<link
rel="stylesheet"
href="https://unpkg.com/leaflet/dist/leaflet.css"
/>

<style>

#map{
    height:500px;
    border-radius:10px;
    margin-top:20px;
}

</style>

</head>
<body>

<div class="container" style="max-width:1000px;">

    <h1>Mapa de Denúncias</h1>

    <div id="map"></div>

</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>

var map = L.map('map').setView([-15.646, -47.789], 12);

L.tileLayer(
    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
{
    attribution:'OpenStreetMap'
}).addTo(map);

<?php

while($dados = $resultado->fetch_assoc()){

    if($dados['latitude'] != ""){

        echo "

        L.marker([
            $dados[latitude],
            $dados[longitude]
        ])
        .addTo(map)
        .bindPopup(`
            <b>$dados[protocolo]</b><br>
            $dados[status]
        `);

        ";

    }

}

?>

</script>

</body>
</html>