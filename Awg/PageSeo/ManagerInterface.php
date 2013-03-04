<?php
/**
 * Date: 28.02.13
 * Time: 18:45
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo;

use Awg\PageSeo\Render\RendererInterface;

interface ManagerInterface extends \ArrayAccess, RendererInterface
{
  /**
   * @return array
   */
  public function getCurrentRouteConfiguration();

  /**
   * @param string $routeName
   * @return array
   */
  public function getRouteConfiguration($routeName);

  /**
   * @param array|string $route
   * @param mixed $context
   * @return string
   */
  public function renderDescription($route, $context);

  /**
   * @param array|string $route
   * @param mixed $context
   * @return string
   */
  public function renderKeywords($route, $context);

  /**
   * @param array|string $route
   * @param mixed $context
   * @return string
   */
  public function renderText($route, $context);

  /**
   * @param array|string $route
   * @param mixed $context
   * @return string
   */
  public function renderTitle($route, $context);
}
