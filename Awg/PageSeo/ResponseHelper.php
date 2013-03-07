<?php
/**
 * Date: 28.02.13
 * Time: 19:09
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo;

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
   * @var \sfPatternRouting
   */
  protected $routing;

  /**
   * @param Manager $manager
   * @param \sfPatternRouting $routing
   * @param \sfWebResponse $response
   */
  function __construct($manager, $routing, $response)
  {
    $this->manager = $manager;
    $this->routing = $routing;
    $this->response = $response;
  }

  /**
   * @param mixed $context
   */
  public function setTitle($context)
  {
    $route = $this->routing->getCurrentRouteName();
    $this->response->setTitle($this->manager->renderTitle($route, $context));
  }

  /**
   * @param mixed $context
   */
  public function setDescription($context)
  {
    $route = $this->routing->getCurrentRouteName();
    $this->response->addMeta('description', $this->manager->renderDescription($route, $context));
  }

  /**
   * @param mixed $context
   */
  public function setKeywords($context)
  {
    $route = $this->routing->getCurrentRouteName();
    $this->response->addMeta('keywords', $this->manager->renderKeywords($route, $context));
  }

  /**
   * @param mixed $context
   */
  public function setMetas($context)
  {
    $route = $this->routing->getCurrentRouteName();
    $this->response->setTitle($this->manager->renderTitle($route, $context));
    $this->response->addMeta('description', $this->manager->renderDescription($route, $context));
    $this->response->addMeta('keywords', $this->manager->renderKeywords($route, $context));
  }
}
