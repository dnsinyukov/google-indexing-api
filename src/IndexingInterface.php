<?php

namespace Dnsinyukov\GoogleIndexingApi;

interface IndexingInterface
{
    public function updateUrl(string $url);
    public function removeUrl(string $url);
    public function batch(array $urls);
}