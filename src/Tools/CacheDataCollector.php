<?php 
namespace App\Tools;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;

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
        $this->calls[__FUNCTION__][] = ['key'=>$key];
        return $this->real->{__FUNCTION__}($key);
    }

    public function hasItem($key)
    {
        $this->calls[__FUNCTION__][] = ['key'=>$key];
        return $this->real->{__FUNCTION__}($key);
    }

    public function deleteItem($key)
    {
        $this->calls[__FUNCTION__][] = ['key'=>$key];
        return $this->real->{__FUNCTION__}($key);
    }

    public function getItems(array $keys = array())
    {
        $this->calls[__FUNCTION__][] = ['keys'=>$keys];
        return $this->real->{__FUNCTION__}($keys);
    }

    public function deleteItems(array $keys)
    {
        $this->calls[__FUNCTION__][] = ['keys'=>$keys];
        return $this->real->{__FUNCTION__}($keys);
    }

    public function clear()
    {
        $this->calls[__FUNCTION__][] = [];
        return $this->real->{__FUNCTION__}($keys);
    }

    public function commit()
    {
        $this->calls[__FUNCTION__][] = [];
        return $this->real->{__FUNCTION__}($keys);
    }

    public function save(CacheItemInterface $item) 
    {
        $this->calls[__FUNCTION__][] = ['item'=>$item->getKey()];
        return $this->real->{__FUNCTION__}($item);

    }

    public function saveDeferred(CacheItemInterface $item)
    {
        $this->calls[__FUNCTION__][] = ['item'=>$item->getKey()];
        return $this->real->{__FUNCTION__}($item);

    }

    public function __call(string $name, array $args) {
        $this->calls[$name][] = $args;
        return call_user_func_array([$this->real, $name], $args);
    }
}