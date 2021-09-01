<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividad 3</title>
</head>

<body>
    <h1>Conteo de votos</h1>
    <hr>
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

    var_dump($contadores);
    ?>
</body>

</html>