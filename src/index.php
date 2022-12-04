<?php

require __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable( __DIR__ . '/../');
$dotenv->load();

$configPath = __DIR__ . '/../configs';

$googleIndexingEngine = new \GoogleIndexingApi\GoogleIndexingAPI();

$fp = fopen(__DIR__ .'/urls.txt', 'r');

while (($line = fgets($fp)) !== false) {

    $url = preg_replace('~\s~', '', $line);

    $googleIndexingEngine->updateUrl($url);

    sleep(rand(1, 2));
}

fclose($fp);
