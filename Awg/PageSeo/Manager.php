<?php
/**
 * Date: 28.02.13
 * Time: 18:45
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo;

use Awg\PageSeo\Exception\UndefinedKeyException;
use Awg\PageSeo\Exception\UndefinedPlaceholderException;

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
   * @param array $configuration
   * @param \Awg\PageSeo\Render\RendererInterface $renderer
   */
  public function __construct($configuration, $renderer)
  {
    // $this->i18n = sfContext::getInstance()->getI18N();
    $this->configuration = $configuration;
    $this->renderer = $renderer;
  }

  /**
   * @param string $routeName
   * @throws Exception\UndefinedKeyException
   * @return array
   */
  public function getRouteConfiguration($routeName)
  {
    if (!isset($this->configuration[$routeName]))
    {
      throw new UndefinedKeyException(sprintf('There is no SEO configuration defined for route "%s"', $routeName));
    }
    return $this->configuration[$routeName];
  }

  /**
   * @param string $route
   * @param mixed $context
   * @return string
   */
  public function renderDescription($route, $context)
  {
    return $this->renderComponent($route, 'description', $context);
  }

  /**
   * @param string $route
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

  /**
   * @param string $route route name
   * @param string $component
   * @param mixed $context
   * @return string
   *
   * @throws Exception\UndefinedPlaceholderException
   */
  public function renderComponent($route, $component, $context)
  {
    try
    {
      $configuration = $this->getRouteConfiguration($route);
      return $this->renderer->renderComponent($configuration, $component, $context);
    }
    catch (UndefinedPlaceholderException $e)
    {
      if (is_string($route)) // in this case we can provide additional useful debug info
      {
        throw new UndefinedPlaceholderException(
          /* message */ sprintf(
            'Error rendering "%s" component for route "%s": %s',
            $component, $route, $e->getMessage()
          ),
          /* placeholder */ $e->getPlaceholder(),
          /* previous exception */ $e);
      }
      else
      {
        throw $e;
      }
    }
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
