<?php

require __DIR__ . '/../vendor/autoload.php';

use Felipecwb\Container;

$container = new Container();

$container->set('message', "Tests..." . PHP_EOL);
$container->set('config', function ($c) {
    return [
        'environment' => 'dev',
        'message'     => $c->get('message')
    ];
});
$container->set('square', function ($c) {
    return function ($n) {
        return $n * 2;
    };
});

echo $container->getMessage();
//Tests...

print_r($container->getConfig());
//Array(
//  [environment] => dev,
//  [message] => Tests...
//
//)

$square = $container->get('square');
var_dump($square(2));
//int(4)
