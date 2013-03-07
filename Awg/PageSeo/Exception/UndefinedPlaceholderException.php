<?php
/**
 * Date: 07.03.13
 * Time: 15:05
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Exception;

class UndefinedPlaceholderException extends \Exception
{
  protected $placeholder;

  public function __construct($message = "", $placeholder = '', \Exception $previous = null)
  {
    $this->placeholder = $placeholder;
    parent::__construct($message, 0, $previous);
  }

  public function getPlaceholder()
  {
    return $this->placeholder;
  }
}
