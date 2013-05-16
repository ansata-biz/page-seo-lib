<?php
/**
 * Date: 10.04.13
 * Time: 20:06
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Configuration;


use Traversable;

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
    $out = array();
    foreach ($configuration as $key => $config)
    {
      $key = explode('?', $key, 2);
      $pattern = array_key_exists(1, $key) ? $key[1] : "*";
      $key = $key[0];

      if (!array_key_exists($key, $out))
      {
        $out[$key] = array();
      }

      $out[$key][$pattern] = $config;
    }

    $this->configuration = $configuration;
    $this->indexedConfiguration = $out;
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
   * @param $offset
   * @return string
   */
  private function match($offset)
  {
    $request = explode("?", $offset, 2);

    if (array_key_exists($offset, $this->cache))
    {
      return $this->cache[$offset];
    }

    if (!array_key_exists($request[0], $this->indexedConfiguration))
    {
      $this->cache[$offset] = false;
    }
    else
    {
      $best = null;
      foreach ($this->indexedConfiguration[$request[0]] as $pattern => $config)
      {
        $key = $request[0] . (($pattern == "*") ? '' : '?' . $pattern);
        if ($pattern == "*")
        {
          $mark = .5;
        }
        else
        {
          $mark = $this->matchKey($offset, $request[0], $pattern !== "*" ? $pattern : '');
        }

        if ($mark > $best)
        {
          $this->cache[$offset] = $key;
          $best = $mark;
        }
      }
      if ($best === null)
      {
        $this->cache[$offset] = false;
      }
    }

    return $this->cache[$offset];
  }

  /**
   * @param string $uri Current uri
   * @param string $key Configuration index key
   * @param string $pattern Pattern defined in config
   * @return int matching mark
   */
  private function matchKey($uri, $key, $pattern)
  {
    $uriComponents = explode('?', $uri, 2);
    $patternComponents = array($key, $pattern);

    // route name does not match
    if ($uriComponents[0] != $key)
    {
      return false;
    }

    // no vars in pattern
    if (count($patternComponents) == 1)
    {
      // match route names => 1 point for route name
      return ($uriComponents[0] == $key) ? 1 : false;
    }

    // pattern requires vars but uri does not have ones
    if (count($uriComponents) == 1 && count($patternComponents) == 2)
    {
      return false;
    }

    // patterns matched - so match vars
    $mark = 1; // 1 point for matching route name
    parse_str($uriComponents[1], $uriVars);
    parse_str($patternComponents[1], $patternVars);

    foreach ($patternVars as $var => $value)
    {
      if (!array_key_exists($var, $uriVars))
      {
        // var no found in $uri
        return false;
      }
      if ($value != $uriVars[$var])
      {
        // var not matched
        return false;
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