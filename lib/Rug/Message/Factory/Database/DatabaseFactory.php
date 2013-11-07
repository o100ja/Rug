<?php

namespace Rug\Message\Factory\Database;

use Rug\Connector\Connector;
use Rug\Message\Factory\ServerFactory;

class DatabaseFactory extends ServerFactory {

  protected $_db;

  public function __construct(Connector $connector, $db) {
    parent::__construct($connector);
    $this->_db = $db;
  }

  public function getDB() {
    return $this->_db;
  }

  public function path($path = '') {
    if (empty($path)) {
      return parent::path($this->_db);
    }
    return parent::path($this->_db . '/' . $path);
  }

}
