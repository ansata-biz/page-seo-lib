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
   * @param string $string
   * @param mixed $context
   * @return string
   */
  public function renderString($string, $context)
  {
    return $this->engine->renderString($string, $context);
  }

  /**
   * @param array $routeConfiguration
   * @param $component
   * @param mixed $context
   * @return string
   */
  public function renderComponent($routeConfiguration, $component, $context)
  {
    return isset($routeConfiguration[$component]) ? $this->renderString($routeConfiguration[$component], $context) : null;
  }
}
