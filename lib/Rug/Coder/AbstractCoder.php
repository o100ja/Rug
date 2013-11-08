<?php
namespace Rug\Coder;

abstract class AbstractCoder {

  /**
   * @var array
   */
  private $_options;

  public function __construct(array $options = array()) {
    $this->_options = $options;
  }

  abstract public function encode($data);

  abstract public function decode($data);

}
 