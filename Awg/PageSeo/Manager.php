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
   * @param string $key
   * @return bool
   */
  public function hasConfiguration($key)
  {
    return isset($this->configuration[$key]);
  }

  /**
   * @param string $key
   * @throws Exception\UndefinedKeyException
   * @return array
   */
  public function getConfiguration($key)
  {
    if (!isset($this->configuration[$key]))
    {
      throw new UndefinedKeyException(sprintf('There is no SEO configuration defined for route "%s"', $key));
    }
    return $this->configuration[$key];
  }

  /**
   * @param string $key
   * @param mixed $vars
   * @return string
   */
  public function renderDescription($key, $vars)
  {
    return $this->renderComponent($key, 'description', $vars);
  }

  /**
   * @param string $key
   * @param mixed $vars
   * @return string
   */
  public function renderKeywords($key, $vars)
  {
    return $this->renderComponent($key, 'keywords', $vars);
  }

  /**
   * @param array|string $key
   * @param mixed $vars
   * @return string
   */
  public function renderText($key, $vars)
  {
    return $this->renderComponent($key, 'text', $vars);
  }

  /**
   * @param array|string $key
   * @param mixed $vars
   * @return string
   */
  public function renderTitle($key, $vars)
  {
    return $this->renderComponent($key, 'title', $vars);
  }

  /**
   * @param string $key route name
   * @param string $component
   * @param mixed $vars
   *
   * @throws Exception\UndefinedPlaceholderException|\Exception
   * @return string
   */
  public function renderComponent($key, $component, $vars)
  {
    try
    {
      $configuration = $this->getConfiguration($key);
      return $this->renderer->renderComponent($configuration, $component, $vars);
    }
    catch (UndefinedPlaceholderException $e)
    {
      if (is_string($key)) // in this case we can provide additional useful debug info
      {
        throw new UndefinedPlaceholderException(
          /* message */ sprintf(
            'Error rendering "%s" component for route "%s": %s',
            $component, $key, $e->getMessage()
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
   * @param string $template
   * @param mixed $vars
   * @return string
   */
  public function renderString($template, $vars)
  {
    return $this->renderer->renderString($template, $vars);
  }

  public function offsetExists($key)
  {
    return isset($this->configuration[$key]);
  }

  public function offsetGet($key)
  {
    return $this->getConfiguration($key);
  }

  public function offsetSet($key, $value)
  {
    $this->configuration[$key] = $value;
  }

  public function offsetUnset($key)
  {
    unset($this->configuration[$key]);
  }
}
