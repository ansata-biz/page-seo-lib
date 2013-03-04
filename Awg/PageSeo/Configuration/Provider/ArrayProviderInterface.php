<?php
/**
 * Date: 28.02.13
 * Time: 18:39
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Configuration\Provider;

interface ArrayProviderInterface
{
  /**
   * @param string $routeName
   * @return array
   * @throws \InvalidArgumentException if there is no configuration defined for given route
   */
  public function getRouteConfigurationArray($routeName);
}
