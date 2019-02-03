<?php
include 'includes/db.php';
?><!doctype html>
<html lang="de">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/roboto.css">
    <link rel="stylesheet" href="css/akut-bootstrap.min.css">
    <style>
        .header-image {
            max-height: 6rem;
            max-width: 100%;
        }

        .title-column {
            width: 60%;
        }
    </style>

    <title>AKUT | Bekanntmachungen</title>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8 text-left">
            <a href="./"><img src="css/header-left.svg" class="header-image" alt="akut extra"/></a>
        </div>
        <div class="col-lg-4 text-right">
            <img src="css/header-right.svg" class="header-image" alt="Bekanntmachungen der Studierendenschaft"/>
        </div>
    </div>

    <hr>

    <div class="text-center">
        <p> Gemäß <b>§§ 36</b> und <b>37</b> der
            <a href="https://www.sp.uni-bonn.de/dokumente/idx/Satzungen/SdS.html#%C2%A736">Satzung der
                Studierendenschaft</a> werden offizielle Bekanntmachungen aller Gremien als AKUT Extra hier bekannt
            gemacht.<br>
            Sofern nicht anders geregelt, treten alle Bekanntmachungen mit ihrer Veröffentlichung an dieser Stelle
            in Kraft. </p>
        <p> Um ein Dokument zu veröffentlichen, sind die
            <a href="./Formatvorgaben-AKUT-extra.pdf">Vorgaben zur Bekanntmachung</a> zu beachten.</p>
    </div>

    <div class="input-group mt-5">
        <div class="input-group-prepend">
            <div class="input-group-text">Filter</div>
        </div>
        <input type="text" class="form-control" id="filterInput" placeholder="Satzung der Fachs…">
    </div>

    <div class="table-responsive">
        <table class="table mt-3">
            <tbody>
            <?php
            $lastYear = 0;
            foreach (readDocuments() as $document) {
                if ($document->year !== $lastYear) {
                    $lastYear = $document->year;
                    echo "<tr id='$lastYear'><th>$lastYear</th><th></th><th></th><th></th></tr>\n";
                }
                echo "{$document->tablerow()}\n";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    function applyFilter() {
        let input, filter, table, tr, a, i;
        input = document.getElementById('filterInput');
        filter = input.value.toLowerCase();
        table = document.querySelectorAll("table")[0];
        tr = table.getElementsByTagName('tr');

        for (i = 0; i < tr.length; i++) {
            a = tr[i].getElementsByTagName("a")[0];
            if (a !== undefined) {
                if (a.innerHTML.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    document.getElementById('filterInput').addEventListener('keyup', function (e) {
        if (e.key === "Escape") {
            document.getElementById('filterInput').value = '';
        }
        applyFilter();
    });

    applyFilter();
</script>
</body>
</html>