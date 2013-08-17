<?php
/**
 * Date: 28.02.13
 * Time: 18:45
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo;

use Awg\PageSeo\Render\RendererInterface;

interface ManagerInterface extends \ArrayAccess, RendererInterface
{
  /**
   * @param string $key
   * @return array
   */
  public function getConfiguration($key);

  /**
   * @param string $key
   * @return bool
   */
  public function hasConfiguration($key);

  /**
   * @param string $key
   * @param mixed $vars
   * @return string
   */
  public function renderDescription($key, $vars);

  /**
   * @param string $key
   * @param mixed $vars
   * @return string
   */
  public function renderKeywords($key, $vars);

  /**
   * @param string $key
   * @param mixed $vars
   * @return string
   */
  public function renderText($key, $vars);

  /**
   * @param string $key
   * @param mixed $vars
   * @return string
   */
  public function renderTitle($key, $vars);
}
