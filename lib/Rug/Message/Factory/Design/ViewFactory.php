<?php

namespace Rug\Message\Factory\Design;

use Rug\Connector\Connector;
use Rug\Message\Factory\DesignFactory;

class ViewFactory extends DesignFactory {

  private $_name;

  public function __construct(Connector $connector, $db, $id, $name) {
    parent::__construct($connector, $db, $id, $name);
    $this->_name = $name;
  }

  public function getName() {
    return $this->_name;
  }

  protected function _path() {
    return parent::_path() . '/_view/' . $this->_name;
  }

}
