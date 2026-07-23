<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$blade = app('blade.compiler');
$content = file_get_contents('resources/views/customer/lapangan/denah.blade.php');
file_put_contents('compiled_test.php', $blade->compileString($content));
echo "Compiled successfully";
