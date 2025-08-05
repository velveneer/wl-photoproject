<?php

use Core\Container;
use Core\Database;
use Core\App;

$container = new Container();

$container -> bind('Core\Database', function () {

    $config = require base_path('config.php');

    return new Database($config['database'], $config['login']['user'], $config['login']['password']);
});

App::setContainer($container);

