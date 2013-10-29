<?php

namespace Rug\Message\Factory;

use Rug\Connector\Connector;

class DatabaseFactory extends ServerFactory {

  protected $_db;

  public function __construct(Connector $connector, $db) {
    parent::__construct($connector);
    $this->_db = $db;
  }

  public function getDB() {
    return $this->_db;
  }

  protected function _path($path = '') {
    if (empty($path)) {
      parent::_path($this->_db);
    }
    return parent::_path($this->_db) . '/' . $path;
  }

}
