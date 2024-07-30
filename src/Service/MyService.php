<?php
namespace App\Service;

use Psr\Cache\CacheItemPoolInterface;

class MyService
{
    private $cache;

    public function __construct(CacheItemPoolInterface $cache)
    {
        $this->cache = $cache;
    }

    public function getExpensiveData()
    {
        $cacheItem = $this->cache->getItem('expensive_data');

        if (!$cacheItem->isHit()) {
          
            $expensiveData = $this->computeExpensiveData();
            $cacheItem->set($expensiveData);
            // Cache pendant 3600 secondes (1 heure)
            $cacheItem->expiresAfter(3600);
            $this->cache->save($cacheItem);
        }

        return $cacheItem->get();
    }

    private function computeExpensiveData()
    {

        return ['data' => 'This is some expensive data to generate'];
    }
}
