<?php
/**
 * Date: 28.02.13
 * Time: 18:45
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo;

use Awg\PageSeo\Configuration\Factory\FactoryInterface;
use Awg\PageSeo\Render\RendererInterface;

interface ManagerInterface extends FactoryInterface, RendererInterface
{
  /**
   * @return Configuration\RouteConfiguration
   */
  public function getCurrentRouteConfiguration();
}
