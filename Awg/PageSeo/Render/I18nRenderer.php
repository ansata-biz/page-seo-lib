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
   * @var string
   */
  protected $defaultCatalogue;

  /**
   * @param \Awg\PageSeo\Render\Engine\EngineInterface $engine
   * @param \sfI18N $i18n
   * @param string $default_catalogue
   */
  function __construct($engine, $i18n, $default_catalogue = 'messages')
  {
    $this->engine = $engine;
    $this->i18n = $i18n;
    $this->defaultCatalogue = $default_catalogue;
  }

  /**
   * @param array $routeConfiguration
   * @param $component
   * @param mixed $context
   * @return string
   */
  public function renderComponent($routeConfiguration, $component, $context)
  {
    $string = $routeConfiguration[$component];
    $catalogue = isset($routeConfiguration['i18n_catalogue']) ? $routeConfiguration['i18n_catalogue'] : $this->defaultCatalogue;
    $string = $this->i18n->__($string, array(), $catalogue);

    $finally = function() use ($string, $context) {
      return $this->engine->renderString($string, $context);
    };

    try
    {
      if ($result = $this->engine->renderString("%html_" . $component . "%", $context))
      {
        return $result;
      }
    }
    catch (\Exception $e)
    {
      return $finally();
    }
    return $finally();
  }

  /**
   * @param string $string
   * @param mixed $context
   * @return string
   */
  public function renderString($string, $context)
  {
    $i18nString = $this->i18n->__($string, array(), $this->defaultCatalogue);
    return $this->engine->renderString($i18nString, $context);
  }
}
