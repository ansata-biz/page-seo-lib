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

  /** @var array|string[] */
  protected $cache = array();

  /**
   * @param array $configuration
   */
  function __construct($configuration)
  {
    $this->configuration = $configuration;
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
    if (!isset($this->cache[$offset]))
    {
      // find a key with highest matching mark
      $best = null;
      foreach ($this->configuration as $key => $value)
      {
        $mark = $this->matchKey($offset, $key);
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
   * @param string $pattern Pattern defined in config
   * @return int matching mark
   */
  private function matchKey($uri, $pattern)
  {
    // exact match
    if ($uri == $pattern)
    {
      return 100; // the perfect pair
    }

    $uriComponents = explode('?', $uri, 2);
    $patternComponents = explode('?', $pattern, 2);

    // route name does not match
    if ($uriComponents[0] != $patternComponents[0])
    {
      return false;
    }

    // no vars in pattern
    if (count($patternComponents) == 1)
    {
      // match route names => 1 point for route name
      return ($uriComponents[0] == $patternComponents[0]) ? 1 : false;
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