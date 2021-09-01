<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Votar</title>
    <style type="text/css" media="screen">
        .partidos {
            display: grid;
            width: min-content;
            grid-auto-flow: column;
            grid-template-rows: 1fr auto;
            align-items: center;
            justify-items: center;
        }

        .partidos>* {
            padding: 10px;
        }
    </style>
    <link rel="stylesheet" href="./votar.css" />
</head>

<body>
    <h1>Votar</h1>
    <a href="conteo.html">Ver conteo</a>
    <br />
    <br />
    <form action="agradecer.php" method="POST">
        <div class="select">
            <label for="persona">Votante:</label>
            <select name="id_votante">
                <?php
                $conn = new mysqli("localhost", "root", null, "votaciones");
                $conn->query("SET NAMES utf8");
                $votantes = $conn->query("SELECT * FROM votante");

                while ($row = $votantes->fetch_assoc()) {
                    echo "
                <option name='id_votante' value='$row[id_votante]'>
                    $row[nombre]
                </option>";
                }
                ?>
            </select>
        </div>
        <br />
        <section class="partidos">
            <input type="radio" name="partido" id="PAN" value="PAN" checked />
            <label for="PAN">
                PAN
                <img height="50" src="./imagenes/pan.png">
            </label>
            <input type="radio" name="partido" id="PRI" value="PRI" />
            <label for="PRI">
                PRI
                <img height="50" src="./imagenes/pri.png">
            </label>
            <input type="radio" name="partido" id="PRD" value="PRD" />
            <label for="PRD">
                PRD
                <img height="50" src="./imagenes/prd.png">
            </label>
            <input type="radio" name="partido" id="MORENA" value="MORENA" />
            <label for="MORENA">
                MORENA
                <img height="50" src="./imagenes/morena.png">
            </label>
        </section>
        <br />
        <input type="submit" value="Votar" />
    </form>

    <script>
    setTimeout(() => {
        /* location.reload() */
    }, 2000);
    </script>
</body>

</html>
