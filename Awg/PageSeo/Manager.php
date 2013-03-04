<?php
/**
 * Date: 28.02.13
 * Time: 18:45
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo;

class Manager implements ManagerInterface
{
  // protected $i18n;

  /**
   * @var \Awg\PageSeo\Configuration\Factory\FactoryInterface
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
   * @param \Awg\PageSeo\Configuration\Factory\FactoryInterface $configuration
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
   * @param string $route
   * @return \Awg\PageSeo\Configuration\RouteConfiguration
   */
  public function getRouteConfiguration($route)
  {
//    if (!isset($this->routesConfiguration[$route]))
//    {
//      $routeConfig = sfConfig::get('app_seo_manager_'.$route, array());
//      $routeConfig['route'] = $route;
//
//      $this->routesConfiguration[$route] = new awgPageSeo($routeConfig, $this->i18n);
//    }

    return $this->configuration->getRouteConfiguration($route);
  }

  /**
   * @return Configuration\RouteConfiguration
   */
  public function getCurrentRouteConfiguration()
  {
    $route = $this->routing->getCurrentRouteName();
    return $this->getRouteConfiguration($route);
  }

  /**
   * @param \Awg\PageSeo\Configuration\RouteConfiguration|string $route
   * @param mixed $context
   * @return string
   */
  public function renderDescription($route, $context)
  {
    $configuration = is_string($route) ? $this->configuration->getRouteConfiguration($route) : $route;
    return $this->renderer->renderDescription($configuration, $context);
  }

  /**
   * @param \Awg\PageSeo\Configuration\RouteConfiguration|string $route
   * @param mixed $context
   * @return string
   */
  public function renderKeywords($route, $context)
  {
    $configuration = is_string($route) ? $this->configuration->getRouteConfiguration($route) : $route;
    return $this->renderer->renderKeywords($configuration, $context);
  }

  /**
   * @param \Awg\PageSeo\Configuration\RouteConfiguration|string $route
   * @param mixed $context
   * @return string
   */
  public function renderText($route, $context)
  {
    $configuration = is_string($route) ? $this->configuration->getRouteConfiguration($route) : $route;
    return $this->renderer->renderText($configuration, $context);
  }

  /**
   * @param \Awg\PageSeo\Configuration\RouteConfiguration|string $route
   * @param mixed $context
   * @return string
   */
  public function renderTitle($route, $context)
  {
    $configuration = is_string($route) ? $this->configuration->getRouteConfiguration($route) : $route;
    return $this->renderer->renderTitle($configuration, $context);
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
}
