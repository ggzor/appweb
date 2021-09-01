<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gracias por votar</title>
    <link rel="stylesheet" href="./agradecer.css" />
</head>

<body>
    <?php
    $id_votante = $_POST['id_votante'];
    $partido = $_POST['partido'];

    $conn = new mysqli("localhost", "root", null, "votaciones");
    $conn->query("SET NAMES utf8");

    $stmt = $conn->prepare("SELECT nombre FROM votante WHERE id_votante = ?");
    $stmt->bind_param("i", $id_votante);
    $stmt->execute();
    $stmt->bind_result($nombre);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO voto (id_votante, partido, fecha) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $id_votante, $partido);
    $stmt->execute();
    $stmt->close();

    $conn->close();

    echo "<h1>Gracias por votar, <strong>$nombre</strong>.</h1>";
    echo "<a href='graficaVotos.php'>Ver conteo</a>";
    ?>
</body>

</html>
