<?php
/**
 * Date: 28.02.13
 * Time: 20:56
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Configuration\Provider;

class InheritanceArrayProviderDecorator implements ArrayProviderInterface
{
  /**
   * @var ArrayProviderInterface
   */
  protected $provider;

  /**
   * @param ArrayProviderInterface $provider
   */
  function __construct($provider)
  {
    $this->provider = $provider;
  }

  /**
   * @param string $routeName
   * @param array $stack
   *
   * @return array
   * @throws \Exception
   */
  protected function getRouteConfigurationArrayInherited($routeName, $stack = array())
  {
    $config = $this->provider->getRouteConfigurationArray($routeName);
    if (isset($config['inherit']) && $config['inherit'][0] == '@')
    {
      $inheritFrom = substr($config['inherit'], 1);
      if (isset($stack[$inheritFrom]))
      {
        throw new \Exception(sprintf(
          "Page Seo route configuration inheritance loop detected: (%s)",
          implode(', ', array_keys($stack))
        ));
      }
      $parent = $this->getRouteConfigurationArrayInherited($inheritFrom, $stack);
      return array_merge($parent, $config);
    }
    return $config;
  }

  /**
   * @param string $routeName
   * @return array
   */
  public function getRouteConfigurationArray($routeName)
  {
    return $this->getRouteConfigurationArrayInherited($routeName);
  }
}
