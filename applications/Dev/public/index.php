<?php
$loader = require __DIR__ . '/../../../vendor/autoload.php';
// Определить в движке системы директорию приложения.
// Передать объект автозагрузчика классов.
// Определить используемое приложение и запустить его.
Karadaras\Engine::appDir(__DIR__ . '/../');
Karadaras\Engine::loader($loader);

$app = Karadaras\Engine::useApplication('dev');
$app->run();

var_dump(get_included_files());