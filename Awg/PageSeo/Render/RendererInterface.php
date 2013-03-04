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
   * @param \Awg\PageSeo\Configuration\RouteConfiguration $configuration
   * @param mixed $context
   * @return string
   */
  public function renderText($configuration, $context);

  /**
   * @param \Awg\PageSeo\Configuration\RouteConfiguration $configuration
   * @param mixed $context
   * @return string
   */
  public function renderTitle($configuration, $context);

  /**
   * @param \Awg\PageSeo\Configuration\RouteConfiguration $configuration
   * @param mixed $context
   * @return string
   */
  public function renderDescription($configuration, $context);

  /**
   * @param \Awg\PageSeo\Configuration\RouteConfiguration $configuration
   * @param mixed $context
   * @return string
   */
  public function renderKeywords($configuration, $context);
}
