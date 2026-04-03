<?php

namespace Dnsinyukov\IndexingApi;

use Google\Exception;
use Google_Client;

class GoogleIndexingAPI implements IndexingInterface
{
    const AUTH_CONFIG_KEY = 'auth_config';

    const INDEXING_URL = 'https://indexing.googleapis.com/v3/urlNotifications:publish';
    const URL_UPDATED = 'URL_UPDATED';
    const URL_DELETED = 'URL_DELETED';

    /**
     * @var Google_Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $urls = [];

    /**
     * @throws Exception
     */
    public function __construct(string $configFile)
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig($configFile);
        $this->client->addScope('https://www.googleapis.com/auth/indexing');
    }

    /**
     * @param string $file
     * @return void
     */
    private function loadConfig(string $file)
    {
        if (!file_exists($file)) {
            throw new \RuntimeException("Config file not found: $file");
        }

        $this->config = json_decode(file_get_contents($file), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("Invalid JSON config file");
        }
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
     * @return string|null
     */
    private function getAuthConfig()
    {
        return $this->config[static::AUTH_CONFIG_KEY] ?? null;
    }

    /**
     * @param string $authConfig
     *
     * @return $this
     *
     * @throws Exception
     */
    public function setAuthConfig(string $authConfig): self
    {
        $this->config[static::AUTH_CONFIG_KEY] = $authConfig;
        $this->client->setAuthConfig($authConfig);

        return $this;
    }

    public function batch(array $urls)
    {
        // TODO: Implement batch() method.
    }

    /**
     * @param string $file
     * @return $this
     */
    public function loadUrlsFromFile(string $file): self
    {
        if (!file_exists($file)) {
            throw new \RuntimeException("URLs file not found: $file");
        }

        $urls = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($urls as $url) {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $this->urls[] = $url;
            }
        }

        return $this;
    }
}