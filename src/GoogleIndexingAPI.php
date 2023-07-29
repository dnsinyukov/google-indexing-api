<?php

namespace Dnsinyukov\IndexingApi;

use Google\Exception;
use Google_Client;

class GoogleIndexingAPI implements IndexingInterface
{
    const INDEXING_URL = 'https://indexing.googleapis.com/v3/urlNotifications:publish';
    const URL_UPDATED = 'URL_UPDATED';
    const URL_DELETED = 'URL_DELETED';

    /**
     * @var Google_Client
     */
    protected $client;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig($this->getAuthConfig());
        $this->client->addScope('https://www.googleapis.com/auth/indexing');
    }

    /**
     * @param string $url
     * @param string $type
     * @return void
     */
    protected function index(string $url, string $type)
    {
        // Get a Guzzle HTTP Client
        $httpClient = $this->client->authorize();

        // Define contents here. The structure of the content is described in the next step.
        $payload = json_encode([
            'url' => $url,
            'type' => $type,
        ]);

        echo 'Indexing URL: ' . $url . PHP_EOL;

        $response = $httpClient->post(self::INDEXING_URL, [
            'body' => $payload
        ]);

        $status_code = $response->getStatusCode();

        echo $status_code . PHP_EOL;

        $body = (string) $response->getBody();

        echo $body . PHP_EOL;
    }

    /**
     * @param string $url
     * @return void
     */
    public function updateUrl(string $url)
    {
        $this->index($url, self::URL_UPDATED);
    }

    /**
     * @param string $url
     * @return void
     */
    public function removeUrl(string $url)
    {
        $this->index($url, self::URL_DELETED);
    }

    /**
     * @return mixed
     */
    private function getAuthConfig()
    {
        return $_ENV['GOOGLE_AUTH_CONFIG'] ?? '';
    }

    public function batch(array $urls)
    {
        // TODO: Implement batch() method.
    }
}