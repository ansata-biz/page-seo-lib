<?php
/**
 * Date: 28.02.13
 * Time: 22:35
 * Author: Ivan Voskoboynyk
 */
namespace Awg\PageSeo\Render;

class I18nRenderer implements RendererInterface
{
  /**
   * @var \sfI18N
   */
  protected $i18n;

  /**
   * @var \Awg\PageSeo\Render\Engine\EngineInterface
   */
  protected $engine;

  /**
   * @param \Awg\PageSeo\Render\Engine\EngineInterface $engine
   * @param \sfI18N $i18n
   */
  function __construct($engine, $i18n)
  {
    $this->engine = $engine;
    $this->i18n = $i18n;
  }

  /**
   * @param \Awg\PageSeo\Configuration\RouteConfiguration $configuration
   * @param mixed $context
   * @return string
   */
  public function renderText($configuration, $context)
  {
    $string = $configuration->getText();
    if ($configuration instanceof \Awg\PageSeo\Configuration\I18nRouteConfiguration)
    {
      $string = $this->i18n->__($string, array(), $configuration->getI18nCatalogue());
    }
    return $this->engine->renderString($string, $context);
  }

  /**
   * @param \Awg\PageSeo\Configuration\RouteConfiguration $configuration
   * @param mixed $context
   * @return string
   */
  public function renderTitle($configuration, $context)
  {
    $string = $configuration->getTitle();
    if ($configuration instanceof \Awg\PageSeo\Configuration\I18nRouteConfiguration)
    {
      $string = $this->i18n->__($string, array(), $configuration->getI18nCatalogue());
    }
    return $this->engine->renderString($string, $context);
  }

  /**
   * @param \Awg\PageSeo\Configuration\RouteConfiguration $configuration
   * @param mixed $context
   * @return string
   */
  public function renderDescription($configuration, $context)
  {
    $string = $configuration->getDescription();
    if ($configuration instanceof \Awg\PageSeo\Configuration\I18nRouteConfiguration)
    {
      $string = $this->i18n->__($string, array(), $configuration->getI18nCatalogue());
    }
    return $this->engine->renderString($string, $context);
  }

  /**
   * @param \Awg\PageSeo\Configuration\RouteConfiguration $configuration
   * @param mixed $context
   * @return string
   */
  public function renderKeywords($configuration, $context)
  {
    $string = $configuration->getKeywords();
    if ($configuration instanceof \Awg\PageSeo\Configuration\I18nRouteConfiguration)
    {
      $string = $this->i18n->__($string, array(), $configuration->getI18nCatalogue());
    }
    return $this->engine->renderString($string, $context);
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
