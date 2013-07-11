<?php
/**
 * Date: 11.07.13
 * Time: 17:25
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Configuration;

class CacheProviderDecorator implements \IteratorAggregate, \ArrayAccess
{
  /** @var array */
  protected $cache;

  /** @var string */
  protected $key;

  /** @var bool */
  protected $modified = false;

  /**
   * @param array|\ArrayAccess $configuration
   * @param \Awg\PageSeo\Cache\CacheStorageInterface $cache
   * @param string $key
   */
  function __construct($configuration, $cache, $key = 'page_seo_configuration_cache')
  {
    $this->configuration = $configuration;
    $this->cacheStorage = $cache;
    $this->key = $key;

    if ($this->cacheStorage->has($key))
    {
      $this->cache = $this->cacheStorage->get($key);
    }
    else
    {
      $this->modified = true;
      $this->cache = array();
    }
  }

  public function getIterator()
  {
    return new \ArrayIterator($this->configuration);
  }

  public function offsetExists($offset)
  {
    if (array_key_exists($offset, $this->cache))
    {
      return isset($this->cache[$offset]);
    }
    $has = isset($this->configuration[$offset]);

    if (!$has)
    {
      $this->modified = true;
      $this->cache[$offset] = null;
    }

    return $has;
  }

  public function offsetGet($offset)
  {
    if (isset($this->cache[$offset]))
    {
      return $this->cache[$offset];
    }
    if (array_key_exists($offset, $this->cache))
    {
      return null;
    }
    return $this->cache[$offset] = $this->configuration[$offset];
  }

  public function offsetSet($offset, $value)
  {
    $this->modified = true;
    $this->cache[$offset] = $value;
    $this->configuration[$offset] = $value;
  }

  public function offsetUnset($offset)
  {
    $this->modified = true;
    unset($this->cache[$offset]);
    unset($this->configuration[$offset]);
  }

  function __destruct()
  {
    if ($this->modified)
    {
      // save cache state
      $this->cacheStorage->set($this->key, $this->cache);
    }
  }
}