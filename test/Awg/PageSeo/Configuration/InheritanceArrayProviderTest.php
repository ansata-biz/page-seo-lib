<?php
/**
 * Date: 09.07.13
 * Time: 16:35
 * Author: Ivan Voskoboynyk
 */

namespace Awg\PageSeo\Configuration;

use Awg\PageSeo\Exception\InheritanceLoopException;
use Awg\PageSeo\Exception\UndefinedKeyException;

class InheritanceArrayProviderTest extends \PHPUnit_Framework_TestCase
{
  public function testBasics()
  {
    $arr = array(
      'route_a' => array(
        'title' => 'the A',
        'description' => 'A description',
      ),
      'route_b' => array(
        'title' => 'the B',
      ),
      'route_c' => array(
        'inherit' => '@route_a',
        'title' => 'the C',
      )
    );

    $config = new InheritanceArrayProvider($arr);

    $this->assertEquals(array_keys(iterator_to_array($config)), array('route_a', 'route_b', 'route_c'), 'Class implements iterator interface');

    $this->assertTrue(isset($config['route_a']), 'Testing if there is route config. Defined one.');
    $this->assertTrue(!isset($config['route_d']), 'Testing if there is route config. Undefined one.');
    $this->assertEquals($config['route_a']['title'], $arr['route_a']['title'], 'Getting route config component');
    $this->assertTrue(!isset($config['route_a']['meta']), 'Testing if route config contains component');
    $this->assertEquals(@$config['route_a']['meta'], null, 'Getting undefined route config component');

    try {
      $d = @$config['route_d'];
      // Getting unexisting route config does not cause exception
    }
    catch (\Exception $e)
    {
      $this->fail('Getting unexisting route config does not cause exception');
    }

    $this->assertEquals($config['route_c']['title'], $arr['route_c']['title'], 'Inheritance leaves own property unchanged');
    $this->assertEquals($config['route_c']['description'], $arr['route_a']['description'], 'Inheritance adds property from parent route config');
  }

  public function testInheritanceLoop()
  {
    // inheritance loop
    $arr = array(
      'route_a' => array(
        'title' => 'the A',
        'inherit' => '@route_c'
      ),
      'route_b' => array(
        'title' => 'the B',
        'inherit' => '@route_a',
      ),
      'route_c' => array(
        'title' => 'the C',
        'inherit' => '@route_b',
      ),
    );

    try {
      $config = new InheritanceArrayProvider($arr);
      $config['route_a'];
      $this->fail('Looped inheritance causes exception.');
    }
    catch (InheritanceLoopException $e)
    {
      // Looped inheritance causes exception
    }
  }

  public function testUnexistingRoute()
  {
    // inherit unexisting route
    $arr = array(
      'route_a' => array(
        'title' => 'the A',
        'inherit' => '@route_b'
      ),
    );

    try {
      $config = new InheritanceArrayProvider($arr);
      $config['route_a'];
      $this->fail('Inheritance from unexisting route config causes exception.');
    }
    catch (UndefinedKeyException $e)
    {
      // Inheritance from unexisting route config causes exception.
    }
  }

  public function testDefaults()
  {
    $arr = array(
      'route_a' => array(
        'title' => 'the A',
      ),
      'route_b' => array(
        'inherit' => '@route_a',
        'title' => 'the B',
        'description' => 'B description'
      )
    );
    $default = array(
      'route_a' => array(
        'description' => 'Lorem ipsum',
        'meta' => 'unit test'
      )
    );
    $config = new InheritanceArrayProvider($arr, $default);

    $this->assertEquals($config['route_a']['title'], $arr['route_a']['title'], 'Using defaults does not affect own route config property.');
    $this->assertEquals($config['route_a']['description'], $default['route_a']['description'], 'Using defaults specifies default route config property.');
    $this->assertEquals($config['route_b']['description'], $arr['route_b']['description'], 'Using defaults and inheritance does not conflict.');
  }
}