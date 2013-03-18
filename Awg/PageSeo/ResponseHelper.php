<?php
/**
 * Date: 28.02.13
 * Time: 19:09
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo;

use Awg\PageSeo\Exception\UndefinedKeyException;

/**
 * Class ResponseHelper helps to set response metas for a given page based on current context
 *
 * @package Awg\PageSeo
 */
class ResponseHelper
{
  /**
   * @var Manager
   */
  protected $manager;

  /**
   * @var \sfWebResponse
   */
  protected $response;

  /**
   * @var ConfigurationHelper
   */
  protected $helper;

  /**
   * @param Manager $manager
   * @param \sfWebResponse $response
   * @param $helper
   */
  function __construct($manager, $response, $helper)
  {
    $this->manager = $manager;
    $this->response = $response;
    $this->helper = $helper;
  }

  /**
   * @param mixed $context$this->request->getUri()
   */
  public function setTitle($context)
  {
    $key = $this->helper->detectCurrentValidFallbackKey();
    $this->response->setTitle($this->manager->renderTitle($key, $context));
  }

  /**
   * @param mixed $context
   */
  public function setDescription($context)
  {
    $key = $this->helper->detectCurrentValidFallbackKey();
    $this->response->addMeta('description', $this->manager->renderDescription($key, $context));
  }

  /**
   * @param mixed $context
   */
  public function setKeywords($context)
  {
    $key = $this->helper->detectCurrentValidFallbackKey();
    $this->response->addMeta('keywords', $this->manager->renderKeywords($key, $context));
  }

  /**
   * @param mixed $context
   * @throws Exception\UndefinedKeyException
   */
  public function setMetas($context)
  {
    $key = $this->helper->detectCurrentValidFallbackKey();

    if ($title = $this->manager->renderTitle($key, $context))
    {
      $this->response->setTitle($title);
    }
    if ($keywords = $this->manager->renderKeywords($key, $context))
    {
      $this->response->addMeta('keywords', $keywords);
    }
    if ($description = $this->manager->renderDescription($key, $context))
    {
      $this->response->addMeta('description', $description);
    }

  }
}