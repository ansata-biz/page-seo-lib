<?php
/**
 * Date: 28.02.13
 * Time: 20:56
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Configuration;

use Awg\PageSeo\Exception\InheritanceLoopException;
use Awg\PageSeo\Exception\UndefinedKeyException;

class InheritanceArrayProvider extends \ArrayObject
{
  /**
   * @param array $config
   * @param array $defaults
   */
  function __construct($config, $defaults = array())
  {
    $processed = array();
    $defaults = (array) $defaults;
    foreach ($config as $key => $value)
    {
      $processed[$key] = $this->getRouteConfigurationArrayInherited($config, $defaults, $key);
    }

    parent::__construct($processed);
  }

  /**
   * @param $configuration
   * @param array $defaults
   * @param string $routeName
   * @param array $stack
   *
   * @throws \Awg\PageSeo\Exception\InheritanceLoopException
   * @throws \Awg\PageSeo\Exception\UndefinedKeyException
   * @return array
   */
  private function getRouteConfigurationArrayInherited(&$configuration, &$defaults, $routeName, $stack = array())
  {
    if (!isset($configuration[$routeName]))
    {
      // act like a regular array when trying to get undefined key
      trigger_error(sprintf('Array key "%s" does not exist', $routeName), E_USER_WARNING);
      return null;
    }

    $config = &$configuration[$routeName];
    // if there is inheritance
    if ($config && isset($config['inherit']) && $config['inherit'][0] == '@')
    {
      $config = array('inherit' => $config['inherit']);
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

      if (!isset($configuration[$inheritFrom]))
      {
        throw new UndefinedKeyException(
          sprintf('Trying to inherit config for %s using undefined key: %s.', $routeName, $inheritFrom),
          $inheritFrom
        );
      }

      // go deeper
      $parent = $this->getRouteConfigurationArrayInherited($configuration, $defaults, $inheritFrom, $stack);
      // merge inherited config with own
      return array_merge($parent, $config);
    }
    // merge default config with own
    return array_merge($defaults, $config);
  }
}
