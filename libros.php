<?php
require_once "funciones.php";

$busqueda = trim($_GET["q"] ?? "");

$libros = [];
$error = null;

if ($busqueda !== "") {

    $parametros = http_build_query([
        "q" => $busqueda,
        "fields" => "key,title,author_name,first_publish_year,cover_i",
        "limit" => 5
    ]);

    $url = "https://openlibrary.org/search.json?" . $parametros;

    $resultado = consumirApiGet($url);

    if ($resultado["ok"]) {
        $libros = $resultado["datos"]["docs"] ?? [];
    } else {
        $error = $resultado["error"];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar libros</title>
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
    article {
        margin-bottom: 20px;
    }
    img {
        display: block;
        margin-top: 10px;
    }
<body>

<h1>Open Library</h1>

<form method="GET">

    <input
        type="text"
        name="q"
        value="<?= htmlspecialchars($busqueda) ?>"
        placeholder="Ej: Gabriel García Márquez"
    >

    <button type="submit">
        Buscar
    </button>

</form>

<hr>

<?php if ($error): ?>

    <p><?= htmlspecialchars($error) ?></p>

<?php elseif ($busqueda !== "" && count($libros) === 0): ?>

    <p>No encontramos resultados.</p>

<?php else: ?>

    <?php foreach ($libros as $libro): ?>

        <article>

            <h2>
                <?= htmlspecialchars($libro["title"] ?? "Sin título") ?>
            </h2>

            <p>
                Autor:
                <?= htmlspecialchars($libro["author_name"][0] ?? "No disponible") ?>
            </p>

            <p>
                Primera publicación:
                <?= htmlspecialchars(
                    (string) ($libro["first_publish_year"] ?? "No disponible")
                ) ?>
            </p>

            <?php if (!empty($libro["cover_i"])): ?>

                <img
                    src="https://covers.openlibrary.org/b/id/<?= $libro["cover_i"] ?>-M.jpg"
                    width="132"
                    alt="Portada"
                >

            <?php endif; ?>

        </article>

        <hr>

    <?php endforeach; ?>

<?php endif; ?>

<a href="index.php">Menu</a>

</body>
</html>