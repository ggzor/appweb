<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votos</title>
</head>

<body>
    <h1>¡Vota por tu partido!</h1>
    <hr>

    <form action="agradecer.php" method="POST">
        Introduce tu nombre:
        <input type="text" name="nombre" required>
        <br>
        <br>
        Selecciona el partido de tu preferencia: <br>
        <input type="radio" name="partido" value="PRI"> PRI
        <input type="radio" name="partido" value="PAN"> PAN
        <input type="radio" name="partido" value="PRD"> PRD
        <input type="radio" name="partido" value="MORENA" checked> MORENA
        <input type="radio" name="partido" value="NULO"> Anular voto
        <br>
        <br>
        <input type="submit" name="enviar" value=" Enviar voto ">
    </form>

</body>

</html>