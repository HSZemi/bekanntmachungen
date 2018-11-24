<?php

include_once 'db.php';

function handleUpload($username)
{
    if (isset($_POST['title']) &&
        isset($_POST['number']) &&
        isset($_POST['year']) &&
        isset($_POST['type']) &&
        isset($_FILES['file'])
    ) {
        if ($_POST['title'] === '' ||
            $_POST['number'] === '' ||
            $_POST['year'] === '' ||
            $_POST['type'] === '' ||
            $_FILES['file']['name'] === ''
        ) {
            return 'Bitte fülle das Formular vollständig aus.';
        }

        $title = $_POST['title'];
        $number = intval($_POST['number']);
        $year = intval($_POST['year']);
        $type = $_POST['type'];

        if ($number < 1) {
            return "Die Nummer muss mindestens den Wert 1 haben, sie hat jedoch den Wert $number.";
        }

        if ($year < 1971) {
            return "Das Jahr muss mindestens den Wert 1971 haben, was schon sehr großzügig ist. Es hat jedoch den Wert $year.";
        }

        if ($year > 2037) {
            return "Faszinierend, dass dieses System auch noch im jahr $year benutzt wird. Seit 2037 ist aber Schluss.";
        }

        $target_dir = dirname(__FILE__) . "/../files/$year/";
        $filename = basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $filename;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (file_exists($target_file)) {
            return "Die Zieldatei existiert bereits.";
        }

        if ($fileType != "pdf") {
            return "Bitte lade eine PDF-Datei hoch.";
        }

        if (!is_dir($target_dir)) {
            $success = mkdir($target_dir, 0777);
            if ($success === false) {
                return "Der Zielordner konnte nicht erstellt werden." . $target_dir;
            }
        }

        if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            return "Der Dateiupload ist fehlgeschlagen.";
        }

        $updated = addDocument($title, $number, $year, $type, $filename, $username);
        if (!$updated) {
            return "Das Dokument konnte nicht in der Datenbank gespeichert werden.";
        }
    }
    return '';
}

