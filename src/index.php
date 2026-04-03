<?php

use Dnsinyukov\IndexingApi\GoogleIndexingAPI;
use Dnsinyukov\IndexingApi\IndexingAPI;

require __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable( __DIR__ . '/../');
$dotenv->load();

$configPath = __DIR__ . '/../configs';

$googleIndexer = new IndexingAPI('google', __DIR__ .'/../configs/mangabook-492220-b14f265914b6.json');
$googleIndexer->loadUrlsFromFile('urls.txt');
$googleIndexer->send();
dd(111);
//$results = $googleIndexer->send();
//
//$indexingEngine = new GoogleIndexingAPI();
//$indexingEngine->setAuthConfig()

//$bingIndexingEngine = new \GoogleIndexingApi\BingIndexingAPI('https://auto-toolbox.com/');

$fp = fopen(__DIR__ .'/urls.txt', 'r');

while (($line = fgets($fp)) !== false) {

    $url = preg_replace('~\s~', '', $line);
    dd($url);
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
