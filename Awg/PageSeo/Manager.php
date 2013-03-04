<?php
/**
 * Date: 28.02.13
 * Time: 18:45
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo;

class Manager implements ManagerInterface
{
  /**
   * @var array
   */
  protected $configuration;

  /**
   * @var \Awg\PageSeo\Render\RendererInterface
   */
  protected $renderer;

  /**
   * @var \sfPatternRouting
   */
  protected $routing;

  /**
   * @param array $configuration
   * @param \sfPatternRouting $routing
   * @param \Awg\PageSeo\Render\RendererInterface $renderer
   */
  public function __construct($configuration, $routing, $renderer)
  {
    // $this->i18n = sfContext::getInstance()->getI18N();
    $this->configuration = $configuration;
    $this->routing = $routing;
    $this->renderer = $renderer;
  }

  /**
   * @param string $routeName
   * @return array
   */
  public function getRouteConfiguration($routeName)
  {
    return $this->configuration[$routeName];
  }

  /**
   * @return array
   */
  public function getCurrentRouteConfiguration()
  {
    $route = $this->routing->getCurrentRouteName();
    return $this->getRouteConfiguration($route);
  }

  /**
   * @param array|string $route
   * @param mixed $context
   * @return string
   */
  public function renderDescription($route, $context)
  {
    return $this->renderComponent($route, 'description', $context);
  }

  /**
   * @param array|string $route
   * @param mixed $context
   * @return string
   */
  public function renderKeywords($route, $context)
  {
    return $this->renderComponent($route, 'keywords', $context);
  }

  /**
   * @param array|string $route
   * @param mixed $context
   * @return string
   */
  public function renderText($route, $context)
  {
    return $this->renderComponent($route, 'text', $context);
  }

  /**
   * @param array|string $route
   * @param mixed $context
   * @return string
   */
  public function renderTitle($route, $context)
  {
    return $this->renderComponent($route, 'title', $context);
  }

  public function renderComponent($route, $component, $context)
  {
    $configuration = is_string($route) ? $this->getRouteConfiguration($route) : $route;
    return $this->renderer->renderComponent($configuration, $component, $context);
  }

  /**
   * @param string $string
   * @param mixed $context
   * @return string
   */
  public function renderString($string, $context)
  {
    return $this->renderer->renderString($string, $context);
  }


  public function offsetExists($offset)
  {
    return isset($this->configuration[$offset]);
  }

  public function offsetGet($offset)
  {
    return $this->getRouteConfiguration($offset);
  }

  public function offsetSet($offset, $value)
  {
    $this->configuration[$offset] = $value;
  }

  public function offsetUnset($offset)
  {
    unset($this->configuration[$offset]);
  }
}
