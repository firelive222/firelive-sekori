<?php
require_once "funciones.php";

$raza = strtolower(trim($_GET["raza"] ?? ""));

$imagenes = [];
$error = null;

if ($raza === "") {
    $url = "https://dog.ceo/api/breeds/image/random/1";
} else {
    $url = "https://dog.ceo/api/breed/"
         . urlencode($raza)
         . "/images/random/1";
}

$resultado = consumirApiGet($url);

if ($resultado["ok"]) {
    $imagenes = $resultado["datos"]["message"] ?? [];

    if (!is_array($imagenes)) {
        $imagenes = [$imagenes];
    }

    if (count($imagenes) === 0) {
        $error = "No se encontraron imágenes para la raza indicada.";
    }
} else {
    $error = $resultado["error"];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perros por raza</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }
    h1 {
        color: #333;
    }
    form {
        margin-bottom: 20px;
    }
    input[type="text"] {
        padding: 5px;
        width: 300px;
    }
    button {
        padding: 5px 10px;
    }
    img {
        display: block;
        margin-top: 10px;
    }
<body>

<h1>RAZAS DE PERROS</h1>

<form method="GET">

    <label>Raza:</label>

    <input
        type="text"
        name="raza"
        value="<?= htmlspecialchars($raza) ?>"
        placeholder="hound, beagle, pug, husky"
    >

    <button type="submit">
        Buscar
    </button>

</form>

<hr>

<?php if ($error): ?>

    <p><strong>Error:</strong> perro no existe o no encontrado</p>

<?php elseif (count($imagenes) > 0): ?>

    <h2>
        <?= $raza === "" ? "PERRO Encontrado" : "Raza: " . htmlspecialchars($raza) ?>
    </h2>

    <?php foreach ($imagenes as $imagen): ?>

        <img
            src="<?= htmlspecialchars($imagen) ?>"
            alt="Imagen de perro"
            width="300"
        >

        <br><br>

    <?php endforeach; ?>

<?php endif; ?>

<br>

<a href="perros.php">Volver</a>
<br><br>
<a href="index.php">Menu</a>

</body>
</html>