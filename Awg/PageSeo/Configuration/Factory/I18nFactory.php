<?php
/**
 * Date: 28.02.13
 * Time: 22:01
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Configuration\Factory;

class I18nFactory implements FactoryInterface
{
  /**
   * @var \Awg\PageSeo\Configuration\Provider\ArrayProviderInterface
   */
  protected $provider;

  /**
   * @var string
   */
  protected $route_configuration_class;

  /**
   * @var array
   */
  protected $cache = array();

  /**
   * @param \Awg\PageSeo\Configuration\Provider\ArrayProviderInterface $provider
   * @param string $route_configuration_class
   */
  function __construct($provider, $route_configuration_class = '\Awg\PageSeo\Configuration\I18nRouteConfiguration')
  {
    $this->provider = $provider;
    $this->route_configuration_class = $route_configuration_class;
  }

  /**
   * @param string $routeName
   * @return \Awg\PageSeo\Configuration\RouteConfiguration
   */
  public function getRouteConfiguration($routeName)
  {
    if (!isset($this->cache[$routeName]))
    {
      $config = (array) $this->provider->getRouteConfigurationArray($routeName);
      $this->cache[$routeName] = new $this->route_configuration_class(
        @$config['text'], @$config['title'], @$config['description'], @$config['keywords'], @$config['i18n_catalogue']
      );
    }

    return $this->cache[$routeName];
  }
}
