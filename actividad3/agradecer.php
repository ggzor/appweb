<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gracias por votar</title>
</head>

<body>

    <?php
    $nombre = $_POST['nombre'];
    $partido = $_POST['partido'];

    $fp = fopen("resultados.txt", "a+");
    fprintf($fp, "$nombre $partido\n");
    fclose($fp);

    echo "<h1>Tu voto ha sido registrado.
Gracias por votar, $nombre.</h1>"
    ?>

    <a href="graficaVotos.php">Ver las estadísticas</a>

</body>

</html>