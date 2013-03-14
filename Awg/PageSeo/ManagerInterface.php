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
   * @param string $routeName
   * @return array
   */
  public function getConfiguration($routeName);

  /**
   * @param string $route
   * @param mixed $context
   * @return string
   */
  public function renderDescription($route, $context);

  /**
   * @param string $route
   * @param mixed $context
   * @return string
   */
  public function renderKeywords($route, $context);

  /**
   * @param string $route
   * @param mixed $context
   * @return string
   */
  public function renderText($route, $context);

  /**
   * @param string $route
   * @param mixed $context
   * @return string
   */
  public function renderTitle($route, $context);
}
