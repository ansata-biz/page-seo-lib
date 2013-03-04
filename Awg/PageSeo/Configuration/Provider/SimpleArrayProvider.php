<?php
/**
 * Date: 28.02.13
 * Time: 18:39
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Configuration\Provider;

use \Awg\PageSeo\Configuration\RouteConfiguration;

class SimpleArrayProvider implements ArrayProviderInterface
{
  /**
   * @var array
   */
  protected $config;

  /**
   * @param array $config
   */
  public function __construct($config = array())
  {
    $this->config = $config;
  }

  /**
   * @param $routeName
   * @return array
   * @throws \InvalidArgumentException
   */
  public function getRouteConfigurationArray($routeName)
  {
    if (!isset($this->config[$routeName]))
    {
      throw new \InvalidArgumentException(sprintf('There is no page seo configuration defined for route "%s"',$routeName));
    }

    return $this->config[$routeName];
  }
}
