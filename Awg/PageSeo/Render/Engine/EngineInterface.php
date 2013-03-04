<?php
/**
 * Date: 28.02.13
 * Time: 18:59
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Render\Engine;

interface EngineInterface
{
  /**
   * @param string $string
   * @param mixed $context
   * @return string
   */
  public function renderString($string, $context);
}
