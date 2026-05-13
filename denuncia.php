<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova Denúncia</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h1>Nova Denúncia</h1>

    <form action="salvar_denuncia.php" method="POST" enctype="multipart/form-data">

        <textarea 
            name="descricao"
            placeholder="Descreva o problema..."
            required
        ></textarea>

        <br><br>

        <input type="file" name="imagem">

        <br><br>

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <p id="localizacao">
            Obtendo localização...
        </p>

        <button type="submit" class="botao">
            Enviar denúncia
        </button>

    </form>

</div>

<script>

navigator.geolocation.getCurrentPosition(
    function(position) {

        document.getElementById("latitude").value =
            position.coords.latitude;

        document.getElementById("longitude").value =
            position.coords.longitude;

        document.getElementById("localizacao").innerHTML =
            "Localização capturada com sucesso.";

    },
    function() {

        document.getElementById("localizacao").innerHTML =
            "Não foi possível obter localização.";

    }
);

</script>

</body>
</html>

