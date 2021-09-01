<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividad 3</title>
    <link rel="stylesheet" href="./compartido.css" />
    <link rel="stylesheet" href="graficaVotos-base.css">
    <link rel="stylesheet" href="graficaVotos.css">
</head>

<body>
    <h1>Conteo de votos</h1>
    <a href="votar.php">Votar</a>
    <br>
    <br>
    <?php
    $conn = new mysqli("localhost", "root", null, "votaciones");
    $resultados = $conn->query("
        SELECT partido, COUNT(id_voto) as conteo
        FROM voto
        GROUP BY partido");

    $contadores = [
        "PAN" => 0,
        "PRI" => 0,
        "PRD" => 0,
        "MORENA" => 0,
    ];


    while ($row = $resultados->fetch_assoc()) {
        $contadores[$row['partido']] = intval($row['conteo']);
    }

    $maximo = max($contadores);
    $zero = $maximo === 0 ? 'data-zeroes' : '';

    echo "
<article class='grafica' $zero style='--data-max: $maximo;'>
    ";

    foreach ($contadores as $key => $value) {
      echo "
    <div class='bar' style='--data-count: $value;'></div>";
    }
    ?>

    <img width="40" src="./imagenes/pan.png">
    <img width="40" src="./imagenes/pri.png">
    <img width="40" src="./imagenes/prd.png">
    <img width="40" src="./imagenes/morena.png">
</article>

<script>
/* setTimeout(() => location.reload(), 2000); */
</script>
</body>

</html>
