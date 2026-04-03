<?php

namespace Dnsinyukov\IndexingApi;

interface IndexingInterface
{
    /**
     * @param string $url
     * @return mixed
     */
    public function updateUrl(string $url);

    /**
     * @param string $url
     * @return mixed
     */
    public function removeUrl(string $url);

    /**
     * @param array $urls
     * @return mixed
     */
    public function batch(array $urls);
}