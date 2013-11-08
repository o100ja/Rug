<?php

namespace Rug\Message\Factory\Database;

use Rug\Coder\CoderManager;
use Rug\Connector\Connector;
use Rug\Message\Factory\ServerFactory;

class DatabaseFactory extends ServerFactory {

  /**
   * @var string
   */
  protected $_db;

  public function __construct(CoderManager $coder, Connector $connector, $db) {
    parent::__construct($coder, $connector);
    $this->_db = $db;
  }

  /**
   * @return string
   */
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
