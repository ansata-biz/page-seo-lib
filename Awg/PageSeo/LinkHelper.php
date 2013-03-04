<?php
/**
 * Date: 28.02.13
 * Time: 19:41
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo;

class LinkHelper
{
  /**
   * @var ManagerInterface
   */
  protected $manager;

  /**
   * @var \sfPatternRouting
   */
  // protected $routing;

  /**
   * @param ManagerInterface $manager
   * @internal @param \sfPatternRouting $routing
   */
  public function __construct($manager)
  {
    $this->manager = $manager;
    // $this->routing = $routing;
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

    if ($text)
    {
      $text = $this->manager->renderString($text, $context);
    }
    else
    {
      $text = $this->manager->renderText($route, $context);
    }

    if (isset($attributes['title']))
    {
      $attributes['title'] = $this->manager->renderString($attributes['title'], $context);
    }
    else
    {
      $attributes['title'] = $this->manager->renderTitle($route, $context);
    }

    return link_to($text, $route, $parameters, $attributes);
  }
}
