<?php

class Document
{
    public $title;
    public $number;
    public $year;
    public $type;
    public $filename;
    public $obsolete;
    public $pubdate;

    public static function from($row)
    {
        $document = new Document();
        $document->title = $row['title'];
        $document->number = $row['number'];
        $document->year = $row['year'];
        $document->type = $row['type'];
        $document->filename = $row['filename'];
        $document->obsolete = $row['obsolete'] === 1 ? true : false;
        $document->pubdate = $row['pubdate'];
        return $document;
    }

    function issue()
    {
        return $this->number . "&#8239;/&#8239;" . $this->year;
    }

    function filepath($prefix)
    {
        return "$prefix/files/$this->year/$this->filename";
    }

    function pubdate()
    {
        $dateTime = new DateTime($this->pubdate);
        $dateTime->setTimezone(new DateTimeZone('Europe/Berlin'));
        return "<time datetime='" . $dateTime->format("c") . "'>" . Document::germaniseMonth($dateTime->format("j. F Y")) . "</time>";
    }

    static function germaniseMonth($input)
    {
        $input = str_replace("January", "Januar", $input);
        $input = str_replace("February", "Februar", $input);
        $input = str_replace("March", "März", $input);
        $input = str_replace("May", "Mai", $input);
        $input = str_replace("June", "Juni", $input);
        $input = str_replace("July", "Juli", $input);
        $input = str_replace("October", "Oktober", $input);
        $input = str_replace("December", "Dezember", $input);
        return $input;
    }

    function tablerow($prefix = '.')
    {
        $class = '';
        if ($this->obsolete) {
            $class = ' text-muted';
        }

        $retval = "<tr class='document $class'>";
        $retval .= "<td class='text-right'>" . $this->issue() . "</td>";
        $retval .= "<td class='title-column'><a href='{$this->filepath($prefix)}'>" . htmlspecialchars($this->title) . "</a></td>";
        $retval .= "<td>" . htmlspecialchars($this->type) . "</td>";
        $retval .= "<td>" . $this->pubdate() . "</td>";
        $retval .= "</tr>\n";
        return $retval;
    }
}

function openDb()
{
    $db = new SQLite3(dirname(__FILE__) . "/../data/bekanntmachungen.db");
    return $db;
}

function closeDb($db)
{
    if ($db !== null) {
        $db->close();
    }
}

function readDocuments()
{
    $db = openDb();
    $result = $db->query("SELECT title, number, year, type, filename, obsolete, pubdate FROM documents ORDER BY year DESC, number DESC");
    $documents = [];
    if ($result !== false) {
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $documents[] = Document::from($row);
        }
    }
    closeDb($db);
    return $documents;
}

function addDocument($title, $number, $year, $type, $filename, $username)
{
    $now = new DateTime(null, new DateTimeZone('UTC'));
    $pubdate = $now->format('c');

    $db = openDb();
    $stmt = $db->prepare("INSERT INTO documents(title, number, year, type, filename, pubdate, user) VALUES (:title, :number, :year, :type, :filename, :pubdate, :user)");
    $stmt->bindValue(':title', $title);
    $stmt->bindValue(':number', $number);
    $stmt->bindValue(':year', $year);
    $stmt->bindValue(':type', $type);
    $stmt->bindValue(':filename', $filename);
    $stmt->bindValue(':pubdate', $pubdate);
    $stmt->bindValue(':user', $username);
    $result = $stmt->execute();
    closeDb($db);
    return $result;
}
