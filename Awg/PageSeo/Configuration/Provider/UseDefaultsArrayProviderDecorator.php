<?php
/**
 * Date: 28.02.13
 * Time: 21:57
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Configuration\Provider;

class UseDefaultsArrayProviderDecorator implements ArrayProviderInterface
{
  /**
   * @var ArrayProviderInterface
   */
  protected $provider;

  /**
   * @var array
   */
  protected $defaults;

  /**
   * @param ArrayProviderInterface $provider
   * @param array $defaults
   */
  function __construct($provider, $defaults = array())
  {
    $this->provider = $provider;
    $this->defaults = (array)$defaults;
  }


  /**
   * @param string $routeName
   * @return array
   * @throws \InvalidArgumentException if there is no configuration defined for given route
   */
  public function getRouteConfigurationArray($routeName)
  {
    $config = $this->provider->getRouteConfigurationArray($routeName);
    return array_merge($this->defaults, $config);
  }
}
