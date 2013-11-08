<?php

namespace Rug\Gateway\Database;

use Rug\Coder\CoderManager;
use Rug\Connector\Connector;
use Rug\Gateway\AbstractGateway;

abstract class AbstractDatabaseGateway extends AbstractGateway {

  /**
   * @var string
   */
  private $_db;

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

}
