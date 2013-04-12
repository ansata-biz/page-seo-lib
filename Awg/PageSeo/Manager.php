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
   * @param mixed $context
   * @return string
   */
  public function renderDescription($key, $context)
  {
    return $this->renderComponent($key, 'description', $context);
  }

  /**
   * @param string $key
   * @param mixed $context
   * @return string
   */
  public function renderKeywords($key, $context)
  {
    return $this->renderComponent($key, 'keywords', $context);
  }

  /**
   * @param array|string $key
   * @param mixed $context
   * @return string
   */
  public function renderText($key, $context)
  {
    return $this->renderComponent($key, 'text', $context);
  }

  /**
   * @param array|string $key
   * @param mixed $context
   * @return string
   */
  public function renderTitle($key, $context)
  {
    return $this->renderComponent($key, 'title', $context);
  }

  /**
   * @param string $key route name
   * @param string $component
   * @param mixed $context
   *
   * @throws Exception\UndefinedPlaceholderException|\Exception
   * @return string
   */
  public function renderComponent($key, $component, $context)
  {
    try
    {
      $configuration = $this->getConfiguration($key);
      return $this->renderer->renderComponent($configuration, $component, $context);
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
    return $this->getConfiguration($offset);
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
