<?php
/**
 * Date: 09.07.13
 * Time: 18:05
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Render\Engine;

use Awg\PageSeo\Exception\UndefinedPlaceholderException;

class PlaceholderEngineTest extends \PHPUnit_Framework_TestCase
{
  public function testBasics()
  {
    $engine = new PlaceholderEngine(array('seo', ''), false);

    $this->assertEquals($engine->renderString('Hello!', array()), 'Hello!', 'Using empty array as context');
    $this->assertEquals($engine->renderString('Hello!', null), 'Hello!', 'Using null as context');
    $this->assertEquals($engine->renderString('Hello, %name%!', array('name' => 'world')), 'Hello, world!', 'Simple placeholder');
    $this->assertEquals($engine->renderString('Hello, %name%! My name is %my_name%. %greeting%!', array('name' => 'world', 'my_name' => 'Ivan', 'greeting' => 'Hola')), 'Hello, world! My name is Ivan. Hola!', 'Multiple placeholders');
    $this->assertEquals($engine->renderString('Hello, %person.name%! You are %person.profession%.', array('person' => array('name' => 'John Doe', 'profession' => 'blacksmith'))), 'Hello, John Doe! You are blacksmith.', 'Nested array as context');
    $this->assertEquals($engine->renderString('Hello, %name%!', array()), 'Hello, !', 'Using context with template variables missing');
  }

  public function testStrictMode()
  {
    $engine = new PlaceholderEngine(array('seo', ''), true);

    try
    {
      $res = $engine->renderString('Hello, %name%!', array());
      $this->fail('Rendering string undefined variables in strict mode causes exception.');
    }
    catch (UndefinedPlaceholderException $e)
    {
      // Rendering string undefined variables in strict mode causes exception.');
    }
  }
}