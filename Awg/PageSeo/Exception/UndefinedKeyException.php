<?php
/**
 * Date: 07.03.13
 * Time: 14:57
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Exception;

class UndefinedKeyException extends \Exception
{
  /**
   * @var string
   */
  protected $key;

  public function __construct($message = '', $key = '', \Exception $previous = null)
  {
    $this->key = $key;
    parent::__construct($message, 0, $previous);
  }

  /**
   * @return string
   */
  public function getKey()
  {
    return $this->key;
  }
}
