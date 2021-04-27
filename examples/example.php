<?php

require __DIR__ . '/../vendor/autoload.php';

use Anemaloy\GetMKADDistance\GetYandexDistanceToMKAD;

$test = (new GetYandexDistanceToMKAD)->CheckMkad("Москва, ул. Лужники 2");
var_dump($test);
