<?php
/**
 * Date: 14.03.13
 * Time: 17:16
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo;

use Awg\PageSeo\Exception\UndefinedKeyException;

/**
 * Class ConfigurationHelper handles detection of valid PageSeo configuration key
 *  based on current context environment.
 *
 * It is used by ResponseHelper to set page metas for current page
 *  and breadcrumbs controller to configure breadcrumb text.
 *
 * @package Awg\PageSeo
 */
class ConfigurationHelper
{
  /**
   * @var \sfPatternRouting
   */
  protected $routing;

  /**
   * @var \sfWebRequest
   */
  protected $request;

  /**
   * @var Manager
   */
  protected $manager;

  /**
   * @param Manager $manager
   * @param \sfPatternRouting $routing
   * @param \sfWebRequest $request
   */
  public function __construct($manager, $routing, $request)
  {
    $this->routing = $routing;
    $this->request = $request;
    $this->manager = $manager;
  }


  /**
   * @return string
   */
  public function detectCurrentValidFallbackKey()
  {
    $uri = str_pad(ltrim($this->request->getPathInfo(), '/'), 1, '/', STR_PAD_LEFT);
    // fallback
    $keys = array($uri, $this->routing->getCurrentRouteName());
    return $this->getValidFallbackKey($keys);
  }

  /**
   * @param array|string[] $keys
   * @throws UndefinedKeyException
   * @return string
   */
  public function getValidFallbackKey($keys)
  {
    foreach ($keys as $key)
    {
      try
      {
        $this->manager->getConfiguration($key);
        return $key;
      }
      catch (UndefinedKeyException $e)
      {
        // continue
      }
    }

    throw new UndefinedKeyException(
      sprintf("There is no page seo configuration defined for at least one of keys: %s", implode(', ', $keys)),
      $keys[0], @$e
    );
  }
}