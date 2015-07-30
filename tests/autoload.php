<?php

/*
|--------------------------------------------------------------------------
| Register The Composer Auto Loader
|--------------------------------------------------------------------------
|
*/

$autoloader = require __DIR__ . '/../vendor/autoload.php';
//add the current directory under the \Flexihash\Tests\ namespace so PSR-4 autoloading will work for tests. 
$autoloader->add('Flexihash\\Tests\\', __DIR__);
