<?php
function install()
{
    if (!is_dir("files/2048")) {
        $result = mkdir("files/2048", 0777);
        if (!$result) {
            echo "<div class='alert alert-danger'>Das Verzeichnis <code>files</code> scheint nicht beschreibbar zu sein.</div>";
            return;
        }
    }

    $result = file_put_contents("files/2048/test.txt", "test");
    if ($result === false) {
        echo "<div class='alert alert-danger'>Das Verzeichnis <code>files/2048</code> scheint nicht beschreibbar zu sein.</div>";
        return;
    }

    $result = unlink("files/2048/test.txt");
    if ($result === false) {
        echo "<div class='alert alert-danger'>Die Datei <code>files/2048/test.txt</code> konnte zwar angelegt, aber nicht mehr gelöscht werden.</div>";
        return;
    }

    $result = rmdir("files/2048");
    if ($result === false) {
        echo "<div class='alert alert-danger'>Das Verzeichnis <code>files/2048</code> konnte nicht mehr gelöscht werden.</div>";
        return;
    }

    if (file_exists("data/bekanntmachungen.db")) {
        echo "<div class='alert alert-warning'>Die Datei <code>data/bekanntmachungen.db</code> existiert bereits. Die Installation wurde also bereits ausgeführt.</div>";
        return;
    }

    $db = null;
    try {
        $db = new SQLite3("data/bekanntmachungen.db");
        $db->exec("CREATE TABLE documents (
id INTEGER PRIMARY KEY,
title TEXT NOT NULL,
number INTEGER NOT NULL,
year INTEGER NOT NULL,
type TEXT NOT NULL,
obsolete BOOLEAN NOT NULL DEFAULT 0,
filename TEXT NOT NULL,
pubdate TIMESTAMP NOT NULL,
user TEXT NOT NULL
)");
        echo "<div class='alert alert-success'>Installation erfolgreich.</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Die Installation ist fehlgeschlagen.</div>";
        echo "<pre>$e</pre>";
    } finally {
        if ($db !== null) {
            $db->close();
        }

    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>bekanntmachungen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/roboto.css">
    <link rel="stylesheet" href="css/akut-bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>Installation</h1>
    <?php
    install();
    ?>
</div>
</body>
</html>
