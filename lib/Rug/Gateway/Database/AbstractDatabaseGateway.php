<?php

namespace Rug\Gateway\Database;

use Rug\Connector\Connector;
use Rug\Gateway\AbstractGateway;

abstract class AbstractDatabaseGateway extends AbstractGateway {

  /**
   * @var string
   */
  private $_db;

  /**
   * @param Connector $connector
   * @param string $db
   */
  public function __construct(Connector $connector, $db) {
    parent::__construct($connector);
    $this->_db = $db;
  }

  /**
   * @return string
   */
  public function getDB() {
    return $this->_db;
  }

}
