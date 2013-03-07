<?php
/**
 * Date: 28.02.13
 * Time: 20:56
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Configuration;

use Awg\PageSeo\Exception\InheritanceLoopException;
use Awg\PageSeo\Exception\UndefinedKeyException;

class InheritanceArrayProvider implements \ArrayAccess
{
  /**
   * @var array
   */
  protected $config;

  /**
   * @var array
   */
  protected $defaults;

  /**
   * @param array $config
   * @param array $defaults
   */
  function __construct($config, $defaults = array())
  {
    $this->config = $config;
    $this->defaults = $defaults ? : array();
  }

  /**
   * @param string $routeName
   * @param array $stack
   *
   * @throws \Awg\PageSeo\Exception\InheritanceLoopException
   * @throws \Awg\PageSeo\Exception\UndefinedKeyException
   * @return array
   */
  protected function getRouteConfigurationArrayInherited($routeName, $stack = array())
  {
    if (!isset($this->config[$routeName]))
    {
      // act like a regular array when trying to get undefined key
      trigger_error(sprintf('Array key "%s" does not exist', $routeName), E_USER_WARNING);
      return null;
    }

    $config = $this->config[$routeName];
    // if there is inheritance
    if ($config && isset($config['inherit']) && $config['inherit'][0] == '@')
    {
      // parent route name
      $inheritFrom = substr($config['inherit'], 1);
      // if route is already in an inheritance chain
      if (isset($stack[$inheritFrom]))
      {
        throw new InheritanceLoopException(sprintf(
          "Configuration inheritance loop detected: (%s)",
          implode(', ', array_keys($stack))
        ), $stack);
      }
      // add route to inheritance chain mark
      $stack[$routeName] = true;

      if (!isset($this->config[$inheritFrom]))
      {
        throw new UndefinedKeyException(
          sprintf('Trying to inherit config for %s using undefined key: %s.', $routeName, $inheritFrom),
          $inheritFrom
        );
      }

      // go deeper
      $parent = $this->getRouteConfigurationArrayInherited($inheritFrom, $stack);
      // merge inherited config with own
      return array_merge($parent, $config);
    }
    // merge default config with own
    return array_merge($this->defaults, $config);
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Whether a offset exists
   * @link http://php.net/manual/en/arrayaccess.offsetexists.php
   * @param mixed $offset <p>
   * An offset to check for.
   * </p>
   * @return boolean true on success or false on failure.
   * </p>
   * <p>
   * The return value will be casted to boolean if non-boolean was returned.
   */
  public function offsetExists($offset)
  {
    return isset($this->config[$offset]);
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to retrieve
   * @link http://php.net/manual/en/arrayaccess.offsetget.php
   * @param mixed $offset <p>
   * The offset to retrieve.
   * </p>
   * @return mixed Can return all value types.
   */
  public function offsetGet($offset)
  {
    return $this->getRouteConfigurationArrayInherited($offset);
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to set
   * @link http://php.net/manual/en/arrayaccess.offsetset.php
   * @param mixed $offset <p>
   * The offset to assign the value to.
   * </p>
   * @param mixed $value <p>
   * The value to set.
   * </p>
   * @return void
   */
  public function offsetSet($offset, $value)
  {
    $this->config[$offset] = $value;
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to unset
   * @link http://php.net/manual/en/arrayaccess.offsetunset.php
   * @param mixed $offset <p>
   * The offset to unset.
   * </p>
   * @return void
   */
  public function offsetUnset($offset)
  {
    unset($this->config[$offset]);
  }
}
