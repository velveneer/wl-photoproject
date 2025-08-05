<?php

use Core\App;
use Core\Database; 

$db = App::resolve(Database::class);

$currentUserID = 1;

$id = $_POST['id'];
$queryNote = 'select * from notes where id = :id';

$note = $db -> query($queryNote,[
    'id' => $id
]) -> findOrFail();

authorize($note['user_id'] === $currentUserID);

$queryDeleteNote = "delete from notes where id = :id";

$db -> query($queryDeleteNote, [
    'id' => $id
]);

redirect("/");
