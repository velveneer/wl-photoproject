<?php

use Core\Database;
use Core\App;


$db = App::resolve(Database::class);

$id = "0";
$queryNotes = "select * from notes where id >= ?";
$notes = $db -> query($queryNotes,[$id]) -> findAll();

view("notes/index.view.php", [
    'heading' => 'My Notes',
    'notes' => $notes
]);