<?php
/**
 * Date: 10.04.13
 * Time: 20:06
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Configuration;

class MatcherProviderDecorator implements \ArrayAccess, \IteratorAggregate
{
  /** @var array */
  protected $configuration;

  /** @var  array */
  protected $indexedConfiguration;

  /** @var array|string[] */
  protected $cache = array();

  /**
   * @param array $configuration
   */
  function __construct($configuration)
  {
    $index = array();
    foreach ($configuration as $pattern => $config)
    {
      $parts = explode('?', $pattern, 2);

      $route = $parts[0];
      $paramStr = isset($parts[1]) ? $parts[1] : "*";

      $index[$route][$paramStr] = $config;
      // preset cache results for exact configuration matches
      $this->cache[$pattern] = $pattern;
    }

    $this->configuration = $configuration;
    $this->indexedConfiguration = $index;
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
          $mark = $this->matchKey($reqParamStr, $configParamStr);
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
   * @param string $requestParamStr requested query params string
   * @param string $configParamStr query params string from configuration
   * @return int matching mark
   */
  private function matchKey($requestParamStr, $configParamStr)
  {
    // no request params and 1+ config requirements
    if (!$requestParamStr && $configParamStr)
    {
      return 0;
    }
    // exact string matching
    if ($requestParamStr == $configParamStr)
    {
      return strlen($requestParamStr) > 0 ? substr_count($requestParamStr, '&') + 1 : 0; // = number of vars in str
    }

    /** @var $reqParams array|string[] */
    parse_str($requestParamStr, $reqParams);
    /** @var $configParams array|string[] */
    parse_str($configParamStr, $configParams);

    // pattern requires vars but request does not have ones
    if (count($reqParams) < count($configParams))
    {
      return 0;
    }

    $mark = 0;

    foreach ($configParams as $var => $value)
    {
      if (!array_key_exists($var, $reqParams))
      {
        // var no found in $uri
        return 0;
      }
      if ($value != $reqParams[$var])
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