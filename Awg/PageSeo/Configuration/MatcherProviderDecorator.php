<?php
/**
 * Date: 10.04.13
 * Time: 20:06
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Configuration;

use Awg\PageSeo\Cache\CacheStorageInterface;

class MatcherProviderDecorator implements \ArrayAccess, \IteratorAggregate
{
  /** @var array */
  protected $configuration;

  /** @var  array */
  protected $indexedConfiguration;

  /** @var array */
  protected $vars;

  /** @var array|string[] */
  protected $cache = array();

  /** @var CacheStorageInterface */
  protected $cacheStorage;

  /**
   * @param array $configuration
   * @param CacheStorageInterface $cache
   */
  function __construct($configuration, $cache = null)
  {
    $this->cacheStorage = $cache;
    $this->configuration = $configuration;

    foreach ($this->configuration as $pattern => $config)
    {
      $this->cache[$pattern] = $pattern;
    }

    if ($this->cacheStorage && $index = $this->cacheStorage->get('page_seo_indexed_configuration'))
    {
      $this->indexedConfiguration = $index;
    }
    else
    {
      $index = array();
      foreach ($configuration as $pattern => $config)
      {
        $parts = explode('?', $pattern, 2);

        $route = $parts[0];
        $paramStr = isset($parts[1]) ? $parts[1] : "*";

        $index[$route][$paramStr] = $config;
        // preset cache results for exact configuration matches
      }
      $this->indexedConfiguration = $index;
      if ($this->cacheStorage)
      {
        $this->cacheStorage->set('page_seo_indexed_configuration', $this->indexedConfiguration);
      }
    }

    if ($this->cacheStorage && $vars = $this->cacheStorage->get('page_seo_configuration_vars'))
    {
      $this->vars = $vars;
    }
    else
    {
      $vars = array();
      foreach ($this->indexedConfiguration as $route => $configs)
      {
        $vars[$route] = array();
        foreach ($configs as $paramStr => $config)
        {
          if ($paramStr != '*')
          {
            parse_str($paramStr, $params);
            foreach ($params as $name => $value)
            {
              $vars[$route][$name] = 1;
            }
          }
        }
      }
      $this->vars = $vars;
      if ($this->cacheStorage)
      {
        $this->cacheStorage->set('page_seo_configuration_vars', $this->vars);
      }
    }
  }

  public function offsetExists($offset)
  {
    $key = $this->match($offset);
    return $key !== false;
  }

  public function offsetGet($offset)
  {
    $key = $this->match($offset);
    if ($key === false)
    {
      return null;
    }
    return $this->configuration[$key];
  }

  public function offsetSet($offset, $value)
  {
    $this->cache[$offset] = $offset;
    $this->configuration[$offset] = $value;
  }

  public function offsetUnset($offset)
  {
    unset($this->configuration[$offset]);
  }

  /**
   * Returns best matching configuration key for a request string
   *
   * @param $pattern
   * @return string
   */
  private function match($pattern)
  {
    $parts = explode('?', $pattern, 2);
    $reqParamStr = isset($parts[1]) ? $parts[1] : '';

    if (array_key_exists($pattern, $this->cache))
    {
      return $this->cache[$pattern];
    }

    if (!array_key_exists($parts[0], $this->indexedConfiguration))
    {
      $this->cache[$pattern] = false;
    }
    else
    {
      $reqParams = array();
      if ($reqParamStr && count($this->vars[$parts[0]]) > 0)
      {
        parse_str($reqParamStr, $reqParams);
        $vars = $this->vars[$parts[0]];

        $reqParams = array_intersect_key($reqParams, $vars);
        $reqParamStr = http_build_query($reqParams);

        $pattern = $parts[0].'?'.$reqParamStr;

        if (array_key_exists($pattern, $this->cache))
        {
          return $this->cache[$pattern];
        }
      }

      $best = null;
      foreach ($this->indexedConfiguration[$parts[0]] as $configParamStr => $config)
      {
        $key = $parts[0] . (($configParamStr == "*") ? '' : '?' . $configParamStr);
        if ($configParamStr == "*")
        {
          $mark = .5;
        }
        else
        {
          $mark = $this->matchKey($reqParams, $configParamStr);
        }

        if ($mark > 0 && $mark > $best)
        {
          $this->cache[$pattern] = $key;
          $best = $mark;
        }
      }
      if ($best === null)
      {
        $this->cache[$pattern] = false;
      }
    }

    return $this->cache[$pattern];
  }

  /**
   * @param string|array $requestParams requested query params string
   * @param string $configParamStr query params string from configuration
   * @return int matching mark
   */
  private function matchKey($requestParams, $configParamStr)
  {
    if (is_string($requestParams))
    {
      $requestParamStr = $requestParams;
      /** @var $reqParams array|string[] */
      parse_str($requestParamStr, $requestParams);

    }

    /** @var $configParams array|string[] */
    parse_str($configParamStr, $configParams);

    // pattern requires vars but request does not have ones
    if (count($requestParams) < count($configParams))
    {
      return 0;
    }

    $mark = 0;

    foreach ($configParams as $var => $value)
    {
      if (!array_key_exists($var, $requestParams))
      {
        // var no found in $uri
        return 0;
      }
      if ($value != $requestParams[$var])
      {
        // var not matched
        return 0;
      }
      $mark++;
    }
    // all is ok
    return $mark;
  }

  public function getIterator()
  {
    return new \ArrayIterator($this->configuration);
  }
}