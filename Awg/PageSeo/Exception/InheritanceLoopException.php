<?php
/**
 * Date: 07.03.13
 * Time: 14:55
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Exception;

class InheritanceLoopException extends \Exception
{
  /**
   * @var array
   */
  protected $loop;

  public function __construct($message = "", $loop = array(), \Exception $previous = null)
  {
    $this->loop = $loop;
    parent::__construct($message, 0, $previous);
  }

  /**
   * @return array
   */
  public function getLoop()
  {
    return $this->loop;
  }
}
