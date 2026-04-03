<?php

namespace Dnsinyukov\IndexingApi;

use GuzzleHttp\Client;
use InvalidArgumentException;
use RuntimeException;

class IndexingAPI
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $config;

    /**
     * @var string[]
     */
    private $urls = [];
    
    /**
     * @var string
     */
    private $provider;
    private $configFile;

    /**
     * @param string $provider
     * @param string $configFile
     */
    public function __construct(string $provider, string $configFile)
    {
        $this->client = new Client();
        $this->provider = $provider;
        $this->loadConfig($configFile);
        $this->configFile = $configFile;
    }

    /**
     * @param string $file
     * @return void
     */
    private function loadConfig(string $file): void
    {
        if (!file_exists($file)) {
            throw new RuntimeException("Config file not found: $file");
        }

        $this->config = json_decode(file_get_contents($file), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("Invalid JSON config file");
        }
    }

    /**
     * @param string $file
     * @return $this
     */
    public function loadUrlsFromFile(string $file): self
    {
        if (!file_exists($file)) {
            throw new RuntimeException("URLs file not found: $file");
        }

        $urls = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($urls as $url) {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $this->urls[] = $url;
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function send(): void
    {
        $provider = new GoogleIndexingAPI($this->configFile);

        foreach ($this->urls as $url) {
            $provider->updateUrl($url);
        }
    }
}