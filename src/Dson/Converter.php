<?php

namespace Dson;

/**
 * Class Converter
 * @package Dson
 * This class is used to convert arrays/objects to dson strings and back
 */
class Converter
{
  protected $objectPairJoiners = array(' ? ', ' , ', ' . ', ' ! ');
  protected $arrayPairJoiners = array(' also ', ' and ');
  protected $stringEscapeMap = array(
    '"'   => ' what is? ',
    '\\'  => ' dont know. ',
    '/'  => ' very scare. ',
    'b'  => ' warn. ',
    'f'  => ' much run? ',
    'n'  => ' so freighten. ',
    'r'  => ' stay. ',
    't'  => ' be brave shibe. '
  );

  /**
   * Takes an array or object and dson encodes it
   * @param $input
   * @return string
   */
  public function encode($input)
  {
    $result = $this->recursiveEncode($input);
    //strip extra spaces
    $result = trim(preg_replace('/\s\s+/', ' ', $result));
    return $result;
  }

  /**
   * A recursive encoding helper function
   * @param $input
   * @return string
   */
  protected function recursiveEncode($input)
  {
    $result = '';
    if (is_array($input) === true && $this->isAssoc($input) === false) { //this is a standard array
      //open array context
      $result .= ' so ';
      //setup modulo counter to determine joiner word
      $counter = 0;
      foreach ($input as $cur) {
        //add the array joiner if we need to
        if ($counter > 0) {
          $result .= $this->arrayPairJoiners[$counter % 2];
        }
        $result .= $this->encode($cur);
        $counter++;
      }
      //close array context
      $result .= ' many ';
    } else if (is_object($input) === true || (is_array($input) === true && $this->isAssoc($input) === true)) {
      if (is_object($input) === true) {
        $input = (array)$input;
      }
      //setup modulo counter for
      $counter = 0;
      //open object context
      $result .= ' such ';
      foreach($input as $key => $value)
      {
        //add the object pair joiner if we need to
        if ($counter > 0) {
          $result .= $this->objectPairJoiners[$counter % 4];
        }
        //determine the pair text
        $result .= ' ' . $this->escapeString($key) . ' is ' . $this->recursiveEncode($value);
        $counter++;
      }
      //close object context
      $result .= ' wow ';
    } else if (is_bool($input) === true){ //we have a bool
      //handle special cases for boolean
      if ($input === true) {
        $input = 'yes';
      } else {
        $input = 'no';
      }
      $result .= ' '. $input . ' ';
    } else if (is_int($input) === true || is_double($input) === true) {
      $result .= ' ' . $this->encodeNumber($input);
    } else if (is_string($input) === true) {
      $result .= ' ' . $this->escapeString($input) . ' ';
    } else if (is_null($input) === true) {
      $result .= ' empty ';
    }

    return $result;
  }

  /**
   * converts a normal string into a dson ready string
   * @param $string
   * @return string
   */
  protected function escapeString($string)
  {
    $result = '';
    $length = strlen($string);
    $escaping = false;
    for ($i=0; $i<$length; $i++) {
      if ($escaping === false) {
        if($string[$i] === '\\'){
          $escaping = true;
        } else if ($string[$i] === '"') {
          $result .= $this->stringEscapeMap[$string[$i]];
        } else {
          $result .= $string[$i];
        }
      } else if (strtolower($string[$i]) === 'u') { //unicode escape sequences are special - leave them be
        $result .= '\\u';
        $escaping = false;
      } else { //escaping
        $result .= isset($this->stringEscapeMap[$string[$i]]) ? $this->stringEscapeMap[$string[$i]] : '';
        $escaping = false;
      }
    }
    return '"'.$string.'"';
  }

  /**
   * converts a standard decimal into a dson octal
   * @param $number
   * @return mixed
   */
  protected function encodeNumber($number)
  {
    $octal = decoct($number);
    return str_replace('e', 'very', $octal);
  }

  /**
   * Determines if an array is associative i.e. its keys are non-numeric or not
   * in order starting from 0
   * @param $array
   * @return bool
   */
  protected function isAssoc($array)
  {
    return array_keys($array) !== range(0, count($array) - 1);
  }
}