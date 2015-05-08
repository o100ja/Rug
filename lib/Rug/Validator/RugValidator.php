<?php

namespace Rug\Validator;

use Rug\Exception\InvalidArgumentException;

class RugValidator {

  /********************************************************************************************************************/

  public function boolean($boolean) {
    if (is_bool($boolean)) {
      return $boolean;
    }
    return !empty($boolean);
  }

  public function string($string, $prefix = 'The string') {
    if (!is_string($string)) {
      throw new InvalidArgumentException($prefix . ' must be a string');
    }
    $string = trim($string);
    if (empty($string)) {
      throw new InvalidArgumentException($prefix . ' is empty');
    }
    return $string;
  }

  public function regexp($string, $prefix = 'The string', $pattern = '#^[\w-]+$#') {
    $string = $this->string($string, $prefix);
    if (!preg_match($pattern, $string)) {
      throw new InvalidArgumentException($prefix . ' in invalid');
    }
    return $string;
  }

  public function numeric($number, $prefix = 'The numeric') {
    if (!is_numeric($number)) {
      throw new InvalidArgumentException($prefix . ' is not a number');
    }
    return $number;
  }

  public function url($url, $prefix = 'The URL') {
    $url   = $this->string($url, $prefix);
    $parts = parse_url($url);
    if (empty($parts)) {
      throw new InvalidArgumentException($prefix . ' is invalid');
    }
    return $parts;
  }

  /********************************************************************************************************************/

  public function dbName($name) {
    return $this->regexp($name, 'The database name', '#[\w\d\$\(\)\+\-\/]+#');
  }

  public function dbUrl($url) {
    $url   = $this->string($url, 'The database URL');
    $parts = parse_url($url);
    if (empty($parts)) {
      throw new InvalidArgumentException('The database URL is invalid');
    }
    $this->dbName($parts['path']);
    return $url;
  }

  public function filter($filter) {
    return $this->regexp($filter, 'The filter name', '#^\w+/\w+$#');
  }

  public function count($count) {
    $count = $this->numeric($count, 'The count parameter');
    $count = intval($count);
    if ($count <= 0) {
      throw new InvalidArgumentException('The count parameter must be a positive integer');
    }
    return $count;
  }

  public function id($id) {
    return $this->regexp($id, 'The document ID');
  }

  public function rev($rev) {
    return $this->regexp($rev, 'The document revision');
  }

  public function attribute($name) {
    return $this->regexp($name, 'The attribute name');
  }

  public function view($view) {
    return $this->string($view, 'The view name');
  }

  public function doc($doc) {
    if (empty($doc)) {
      throw new InvalidArgumentException('The document is empty');
    }
    if (is_array($doc)) {
      $doc = (object)$doc;
    }
    return $doc;
  }

  public function data($data) {
    if (empty($data)) {
      throw new InvalidArgumentException('The document is empty');
    }
    if (is_array($data)) {
      $data = (object)$data;
    }
    return $data;
  }

  public function name($name) {
    return $this->regexp($name, 'The attachment name', '#^[\w-\.]+$#');
  }

  public function design($data) {
    return $this->data($data);
  }

  /********************************************************************************************************************/

}
