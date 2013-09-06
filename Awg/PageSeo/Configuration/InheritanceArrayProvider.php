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
    $defaults = (array)$defaults;
    foreach (array_unique(array_merge(array_keys($config), array_keys($defaults))) as $key)
    {
      if (!isset($config[$key]) && isset($defaults[$key])) {
        $config[$key] = $defaults[$key];
      }
      $processed[$key] = $this->getRouteConfigurationArrayInherited($config, $defaults, $key);
    }

    parent::__construct($processed);
  }

  /**
   * @param $configuration
   * @param array $_default
   * @param string $routeName
   * @param array $stack
   *
   * @throws \Awg\PageSeo\Exception\InheritanceLoopException
   * @throws \Awg\PageSeo\Exception\UndefinedKeyException
   * @return array
   */
  private function getRouteConfigurationArrayInherited($configuration, $defaults, $routeName, $stack = array())
  {
    $_default = isset($defaults[$routeName]) ? $defaults[$routeName] : array();
    $_config = isset($configuration[$routeName]) ? $configuration[$routeName] : $_default;
    // if there is inheritance
    if ($_config && isset($_config['inherit']) && $_config['inherit'][0] == '@')
    {
      // parent route name
      $inheritFrom = substr($_config['inherit'], 1);
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
      return array_merge($parent, $_config);
    }
    // merge default config with own
    return array_merge($_default, $_config);
  }
}
