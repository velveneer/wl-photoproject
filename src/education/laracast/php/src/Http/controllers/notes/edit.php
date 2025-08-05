<?php

use Core\Database; 
use Core\App;
use Core\Response;

$db = App::resolve(Database::class);

$currentUserID = 1;

$id = $_GET['id'];
$queryNote = 'select * from notes where id = :id';
$note = $db -> query($queryNote,[
    'id' => $id
]) -> findOrFail();

authorize($note['user_id'] === $currentUserID);

view("notes/edit.view.php", [
    'heading' => 'Edit Note',
    'errors' => [],
    'note' => $note
]);