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
   * @param Manager $manager
   * @param \sfWebResponse $response
   */
  function __construct($manager, $response)
  {
    $this->manager = $manager;
    $this->response = $response;
  }

  /**
   * @param mixed $context
   */
  public function setTitle($context)
  {
    $configuration = $this->manager->getCurrentRouteConfiguration();
    $this->response->setTitle($this->manager->renderTitle($configuration, $context));
  }

  /**
   * @param mixed $context
   */
  public function setDescription($context)
  {
    $configuration = $this->manager->getCurrentRouteConfiguration();
    $this->response->addMeta('description', $this->manager->renderDescription($configuration, $context));
  }

  /**
   * @param mixed $context
   */
  public function setKeywords($context)
  {
    $configuration = $this->manager->getCurrentRouteConfiguration();
    $this->response->addMeta('keywords', $this->manager->renderKeywords($configuration, $context));
  }

  /**
   * @param mixed $context
   */
  public function setMetas($context)
  {
    $configuration = $this->manager->getCurrentRouteConfiguration();
    $this->response->setTitle($this->manager->renderTitle($configuration, $context));
    $this->response->addMeta('description', $this->manager->renderDescription($configuration, $context));
    $this->response->addMeta('keywords', $this->manager->renderKeywords($configuration, $context));
  }
}
