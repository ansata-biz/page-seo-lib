<?php
/**
 * Date: 28.02.13
 * Time: 22:04
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Configuration;

class I18nRouteConfiguration extends RouteConfiguration
{
  /**
   * @var string
   */
  protected $i18nCatalogue = 'messages';

  function __construct($text, $title = null, $description = null, $keywords = null, $i18n_catalogue = null)
  {
    parent::__construct($text, $title, $description, $keywords);
    if ($i18n_catalogue)
    {
      $this->i18nCatalogue = $i18n_catalogue;
    }
  }

  /**
   * @return string
   */
  public function getI18nCatalogue()
  {
    return $this->i18nCatalogue;
  }
}
