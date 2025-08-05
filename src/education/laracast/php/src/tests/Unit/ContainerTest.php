<?php

use Core\Container;
use Tests\TestCase;

test('It can resolve something out of the container', function () {
    
    $container = new Container();

    $container -> bind('foo', fn() => 'bar');
    
    $result = $container -> resolve('foo');

    expect($result) -> toEqual('bar');
});
