<?php

use Core\Database; 
use Core\App;

$db = App::resolve(Database::class);

$currentUserID = 1;

$id = $_GET['id'];
$queryNote = 'select * from notes where id = :id';
$note = $db -> query($queryNote,[
    'id' => $id
]) -> findOrFail();

authorize($note['user_id'] === $currentUserID);

view("notes/show.view.php", [
        'heading' => $heading,
        'note' => $note
]);


