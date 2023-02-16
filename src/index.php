<?php

require __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable( __DIR__ . '/../');
$dotenv->load();

$configPath = __DIR__ . '/../configs';

$googleIndexingEngine = new \GoogleIndexingApi\GoogleIndexingAPI();
//$bingIndexingEngine = new \GoogleIndexingApi\BingIndexingAPI('https://auto-toolbox.com/');

$fp = fopen(__DIR__ .'/urls.txt', 'r');
$client = new \GuzzleHttp\Client();


while (($line = fgets($fp)) !== false) {

    $url = preg_replace('~\s~', '', $line);
//    $status = 200;

//    try {
//        $response = $client->get($url);
//        $status = $response->getStatusCode();
//    } catch (\Exception $exception) {
//        $status = $exception->getCode();
//
////        if ($status === 200) {
////            echo $url . PHP_EOL;
////        }
//    }

//    echo $url . ' : ' . $status . PHP_EOL;

    $googleIndexingEngine->updateUrl($url);
//    $b->updateUrl($url);

    sleep(rand(1, 2));
}

fclose($fp);
