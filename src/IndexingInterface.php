<?php

namespace GoogleIndexingApi;

interface IndexingInterface
{
    public function updateUrl(string $url);
    public function removeUrl(string $url);
}