# PHP Container
A Simple PHP Data Container

`composer require "felipecwb/container"`

```php

use Felipecwb\Container;

$container = new Container();
$container->set('config', [
    'environment' => 'dev',
    'database'    => 'sqlite::memory:'
]);

$container->get('config'); // Array

$container->set('db', function ($c) {
    // Container instance as first argument on closures
    $config = $c->get('config');

    return new PDO(
        $config['database'],
        null,
        null,
        array(PDO::ATTR_PERSISTENT => true)
    );
});

//lazy load
$container->getDb(); //PDO

```
