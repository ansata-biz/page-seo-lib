<?php
/**
 * Date: 28.02.13
 * Time: 22:45
 * Author: Ivan Voskoboynyk
 */
namespace Awg\PageSeo\Render;

use \Awg\PageSeo\Render\Engine\EngineInterface;

interface RendererInterface extends EngineInterface
{
  /**
   * @param string $key
   * @param $component
   * @param mixed $context
   * @return string
   */
  public function renderComponent($key, $component, $context);
}
