<?php

namespace DsonTest;

use Dson\Converter;
use PHPUnit_Framework_TestCase;

/**
 * Class ConverterTest
 * @group unit
 */
class ConverterTest extends PHPUnit_Framework_TestCase
{
  protected $dson;

  public function setUp()
  {
    $this->dson = new Converter();
  }

  public function testInt()
  {
    $input = 5;
    $expected = '5';
    $this->assertEncode($input, $expected);
  }

  public function testString()
  {
    $input = 'foo';
    $expected = '"foo"';
    $this->assertEncode($input, $expected);
  }

  public function testString_Number()
  {
    $input = '5';
    $expected = '"5"';
    $this->assertEncode($input, $expected);
  }

  public function testArray()
  {
    $input = array('foo');
    $expected = 'so "foo" many';
    $this->assertEncode($input, $expected);
  }

  public function testArray_Multiple2()
  {
    $input = array('foo', 'bar');
    $expected = 'so "foo" and "bar" many';
    $this->assertEncode($input, $expected);
  }

  public function testArray_Multiple3()
  {
    $input = array('foo', 'bar', 'baz');
    $expected = 'so "foo" and "bar" also "baz" many';
    $this->assertEncode($input, $expected);
  }

  public function testArray_Multiple4()
  {
    $input = array('foo', 'bar', 'baz', 'qux');
    $expected = 'so "foo" and "bar" also "baz" and "qux" many';
    $this->assertEncode($input, $expected);
  }

  public function testArray_Empty()
  {
    $input = array();
    $expected = 'such wow';
    $this->assertEncode($input, $expected);
  }

  public function testAssocArray()
  {
    $input = array('foo' => 'bar');
    $expected = 'such "foo" is "bar" wow';
    $this->assertEncode($input, $expected);
  }

  protected function assertEncode($input, $expected)
  {
    $actual = $this->dson->encode($input);
    $this->assertEquals($actual, $expected);
  }

  public function testStringEscape()
  {
    $input = 'She said \\ \"Hello\" \n';
    $expected = '"She said dont know. what is?Hellowhat is? so freighten."';
    $this->assertEncode($input, $expected);
  }

//  protected function assertDecode($original, $encoded)
//  {
//    $this->assertEquals($original, $this->dson->decode($this->dson->encode($original)));
//  }
}
