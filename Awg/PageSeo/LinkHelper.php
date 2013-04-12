<?php
/**
 * Date: 28.02.13
 * Time: 19:41
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo;

/**
 * Class LinkHelper generates links with all seo-friendly attributes set basing on configuration for given route
 *
 * @package Awg\PageSeo
 */
class LinkHelper
{
  /**
   * @var ManagerInterface
   */
  protected $manager;

  /**
   * @param ManagerInterface $manager
   */
  public function __construct($manager)
  {
    $this->manager = $manager;
  }

  /**
   * @param string $route
   * @param array $parameters
   * @param array $attributes
   *
   * @return string
   */
  public function generateLinkShortcut($route, $parameters = array(), $attributes = array())
  {
    return $this->generateLink(null, null, $route, $parameters, $attributes);
  }

  /**
   * @param string $text
   * @param mixed $context
   * @param string $route
   * @param array $parameters
   * @param array $attributes
   *
   * @return string
   */
  public function generateLink($text, $context, $route, $parameters = array(), $attributes = array())
  {
    $context = $context ?: $parameters;
    $key = (count($parameters) == 0) ? $route : ($route . '?' . http_build_query($parameters));

    if ($text)
    {
      $text = $this->manager->renderString($text, $context);
    }
    else
    {
      $text = $this->manager->renderText($key, $context);
    }

    if (isset($attributes['title']))
    {
      $attributes['title'] = $this->manager->renderString($attributes['title'], $context);
    }
    else
    {
      $attributes['title'] = $this->manager->renderTitle($key, $context);
    }

    return link_to($text, $route, $parameters, $attributes);
  }
}
