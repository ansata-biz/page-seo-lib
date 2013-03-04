<?php
/**
 * Date: 28.02.13
 * Time: 22:25
 * Author: Ivan Voskoboynyk
 */
namespace Awg\PageSeo\Render;

class BasicRenderer implements RendererInterface
{
  /**
   * @var \Awg\PageSeo\Render\Engine\EngineInterface
   */
  protected $engine;

  /**
   * @param \Awg\PageSeo\Render\Engine\EngineInterface $engine
   */
  function __construct($engine)
  {
    $this->engine = $engine;
  }

  /**
   * @param \Awg\PageSeo\Configuration\RouteConfiguration $configuration
   * @param mixed $context
   * @return string
   */
  public function renderText($configuration, $context)
  {
    return $this->engine->renderString($configuration->getText(), $context);
  }

  /**
   * @param \Awg\PageSeo\Configuration\RouteConfiguration $configuration
   * @param mixed $context
   * @return string
   */
  public function renderTitle($configuration, $context)
  {
    return $this->engine->renderString($configuration->getTitle(), $context);
  }

  /**
   * @param \Awg\PageSeo\Configuration\RouteConfiguration $configuration
   * @param mixed $context
   * @return string
   */
  public function renderDescription($configuration, $context)
  {
    return $this->engine->renderString($configuration->getDescription(), $context);
  }

  /**
   * @param \Awg\PageSeo\Configuration\RouteConfiguration $configuration
   * @param mixed $context
   * @return string
   */
  public function renderKeywords($configuration, $context)
  {
    return $this->engine->renderString($configuration->getKeywords(), $context);
  }

  /**
   * @param string $string
   * @param mixed $context
   * @return string
   */
  public function renderString($string, $context)
  {
    return $this->engine->renderString($string, $context);
  }
}
