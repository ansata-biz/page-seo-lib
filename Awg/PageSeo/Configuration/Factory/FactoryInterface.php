<?php
/**
 * Date: 28.02.13
 * Time: 21:46
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Configuration\Factory;

interface FactoryInterface
{
  /**
   * @param string $routeName
   * @return \Awg\PageSeo\Configuration\RouteConfiguration
   */
  public function getRouteConfiguration($routeName);
}
