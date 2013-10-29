<?php

namespace Rug\Message\Factory;

use Rug\Connector\Connector;

class DatabaseFactory extends AbstractFactory {

  protected $_db;

  public function __construct(Connector $connector, $db) {
    parent::__construct($connector);
    $this->_db = $db;
  }

  public function getDB() {
    return $this->_db;
  }

//
//  public function setDB($db) {
//    return $this->_db = $db;
//  }

  public function createURL($path = '', array $parameters = array()) {
    return $this->_url($this->_root($this->_db . '/' . $path), $parameters);
  }

}
