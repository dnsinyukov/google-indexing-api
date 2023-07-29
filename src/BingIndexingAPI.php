<?php

namespace Dnsinyukov\IndexingApi;

use Google\Exception;
use Google_Client;

class BingIndexingAPI implements IndexingInterface
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;
    /**
     * @var string
     */
    private $siteUrl;

    /**
     * @param string $siteUrl
     */
    public function __construct(string $siteUrl)
    {
        $this->client = new \GuzzleHttp\Client(['base_uri' => 'https://www.bing.com']);
        $this->siteUrl = $siteUrl;
    }

    /**
     * @param array $urls
     */
    public function batch(array $urls)
    {
        $body = json_encode([
            'siteUrl' => $this->siteUrl,
            'urlList' => $urls,
        ]);

        try {
            $response = $this->client->post('/webmaster/api.svc/json/SubmitUrlbatch', [
                'headers' => [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'Host' => 'ssl.bing.com',
                ],
                'query' => [
                    'apikey' => $_ENV['BING_API_KEY']
                ],
                'body' => $body
            ]);
        } catch (\Exception $exception) {
            // TODO
        }


        $status_code = $response->getStatusCode();

        echo $status_code . PHP_EOL;

        $body = (string) $response->getBody();

        echo $body . PHP_EOL;
    }


    public function updateUrl(string $url)
    {
        $body = json_encode([
            'siteUrl' => $this->siteUrl,
            'url' => $url,
        ]);

        try {
            $response = $this->client->post('/webmaster/api.svc/pox/SubmitUrl', [
                'headers' => [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'Host' => 'ssl.bing.com',
                ],
                'query' => [
                    'apikey' => $_ENV['BING_API_KEY']
                ],
                'body' => $body
            ]);

        } catch (\Exception $exception) {
            print_r($exception); exit(1);
            // TODO
        }


        $status_code = $response->getStatusCode();

        echo $status_code . PHP_EOL;

        $body = (string) $response->getBody();

        echo $body . PHP_EOL;
    }

    public function getCrawlIssues(string $url)
    {
        try {
            $response = $this->client->get('/webmaster/api.svc/json/GetDisavowedLinks', [
                'headers' => [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'Host' => 'ssl.bing.com',
                ],
                'query' => [
                    'siteUrl' => $this->siteUrl,
                    'apikey' => $_ENV['BING_API_KEY']
                ]
            ]);
        } catch (\Exception $exception) {
            // TODO
        }

        $status_code = $response->getStatusCode();

        echo $status_code . PHP_EOL;

        $body = (string) $response->getBody();

        print_r($body); exit(1);

        echo $body . PHP_EOL;
    }

    public function removeUrl(string $url)
    {
        // TODO: Implement removeUrl() method.
    }
}