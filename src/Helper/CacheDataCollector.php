<?php
namespace App\Helper;

use Psr\Cache\CacheItemPoolInterface;

class CacheDataCollector implements CacheItemPoolInterface
{
    private $real;
    private $calls;

    public function __construct(CacheItemPoolInterface $cache)
    {
        $this->real = $cache;
    }

    public function getCalls()
    {
        return $this->calls;
    }

    public function getItem($key)
    {
        $this->calls['getItem'][] = ['key'=>$key];
        return $this->real->getItem($key);
    }
}