<?php
include '../includes/db.php';
include '../includes/files.php';

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    die('Login vergessen, was?');
}

$username = $_SERVER['PHP_AUTH_USER'];
$message = "";
$alertclass = '';
$title = '';
$number = '';
$year = '';
$type = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = handleUpload($username);
    if ($message === "") {
        $message = "Upload erfolgreich.";
        $alertclass = 'success';
    } else {
        $alertclass = 'warning';
        $title = isset($_POST['title']) ? $_POST['title'] : 'HURR DURR';
        $number = isset($_POST['number']) ? $_POST['number'] : 'HURR DURR';
        $year = isset($_POST['year']) ? $_POST['year'] : 'HURR DURR';
        $type = isset($_POST['type']) ? $_POST['type'] : 'HURR DURR';
    }
}
?><!doctype html>
<html lang="de">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/roboto.css">
    <link rel="stylesheet" href="../css/akut-bootstrap.min.css">
    <style>
        input[type='number'] {
            -moz-appearance: textfield;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .header-image {
            max-height: 6rem;
            max-width: 100%;
        }

        .title-column {
            width: 60%;
        }
    </style>

    <title>AKUT | Bekanntmachungen ~ Admin</title>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8 text-left">
            <a href="./"><img src="../css/header-left.svg" class="header-image" alt="akut extra"/></a>
        </div>
        <div class="col-lg-4 text-right">
            <a href="../"><img src="../css/header-right.svg" class="header-image"
                               alt="Bekanntmachungen der Studierendenschaft"/></a>
        </div>
    </div>

    <hr>

    Hallo, <?php echo $username; ?>.

    <h2>Neue Bekanntmachung</h2>

    <?php
    if ($message !== "") {
        echo "<div class='alert alert-$alertclass' role='alert'>$message</div>";
    }
    ?>

    <div class="bg-secondary text-white p-3">
        <form method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-9">
                    <div class="form-group">
                        <label for="inputTitle">Titel des Dokuments</label>
                        <input type="text" class="form-control" id="inputTitle" name="title"
                               value="<?php echo htmlspecialchars($title); ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputFile">PDF-Datei</label>
                        <input type="file" class="form-control-file" id="inputFile" name="file">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="inputNumber">Nummer / Jahr</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="inputNumber" name="number"
                                   value="<?php echo htmlspecialchars($number); ?>">
                            <div class="input-group-append">
                                <span class="input-group-text">/</span>
                            </div>
                            <input type="number" class="form-control" id="inputYear" name="year"
                                   value="<?php echo htmlspecialchars($year); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="selectType">Typ des Dokuments</label>
                        <select class="form-control" id="selectType" name="type">
                            <option selected><?php echo htmlspecialchars($type); ?></option>
                            <option>Satzung</option>
                            <option>Ordnung</option>
                            <option>Sonstige</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Bekannt machen!</button>
        </form>
    </div>


    <div class="mt-4">
        <h2>Alle Bekanntmachungen</h2>

        <div class="table-responsive">
            <table class="table">
                <tbody>
                <?php
                $lastYear = 0;
                foreach (readDocuments() as $document) {
                    if ($document->year !== $lastYear) {
                        $lastYear = $document->year;
                        echo "<tr id='$lastYear'><th>$lastYear</th><th></th><th></th><th></th></tr>\n";
                    }
                    echo "{$document->tablerow("..")}\n";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>