<?php

use Core\Database; 
use Core\App;
use Core\Validator;

$db = App::resolve(Database::class);

$currentUserID = 1;

$id = $_POST['id'];
$queryNote = 'select * from notes where id = :id';
$note = $db -> query($queryNote,[
    'id' => $id
]) -> findOrFail();

authorize($note['user_id'] === $currentUserID);

$errors = [];

if (! Validator::string($_POST['body'], 1, 1000)) {
    $errors['body'] = 'A body of no more than 1000 characters is needed';
}

if (count($errors)) {
    return view('notes/edit.view.php', [
        'heading' => $heading,
        'errors' => $errors,
        'note' => $note

    ]);
}

$db -> query('update notes set body = :body where id = :id', [
    'id' => $_POST['id'],
    'body' => $_POST['body']
]);

redirect("/");