<?php
/**
 * Date: 28.02.13
 * Time: 19:13
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Configuration;

class RouteConfiguration
{
  /**
   * @var string
   */
  protected $text;

  /**
   * @var string
   */
  protected $description;

  /**
   * @var string
   */
  protected $keywords;

  /**
   * @var string
   */
  protected $title;

  /**
   * @param string $text
   * @param string $title
   * @param string $description
   * @param string $keywords
   */
  function __construct($text, $title = null, $description = null, $keywords = null)
  {
    $this->text = $text;
    $this->title = $title;
    $this->description = $description;
    $this->keywords = $keywords;
  }

  /**
   * @return string
   */
  public function getDescription()
  {
    return $this->description;
  }

  /**
   * @return string
   */
  public function getKeywords()
  {
    return $this->keywords;
  }

  /**
   * @return string
   */
  public function getText()
  {
    return $this->text;
  }

  /**
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }
}
