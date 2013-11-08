<?php

namespace Rug\Message\Factory\Database\Document\Design;

use Rug\Coder\CoderManager;
use Rug\Connector\Connector;
use Rug\Message\Factory\Database\Document\DesignFactory;

abstract class AbstractSectionFactory extends DesignFactory {

  private $_name;
  private $_prefix;

  public function __construct(CoderManager $coder, Connector $connector, $db, $id, $name, $prefix) {
    parent::__construct($coder, $connector, $db, $id);
    $this->_name   = $name;
    $this->_prefix = $prefix;
  }

  public function getName() {
    return $this->_name;
  }

  protected function _path() {
    return parent::_path() . '/' . $this->_prefix . '/' . $this->_name;
  }

}
