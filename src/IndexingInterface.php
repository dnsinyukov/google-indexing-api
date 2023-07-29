<?php

namespace Dnsinyukov\IndexingApi;

interface IndexingInterface
{
    public function updateUrl(string $url);
    public function removeUrl(string $url);
    public function batch(array $urls);
}