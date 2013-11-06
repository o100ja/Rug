<?php

namespace Rug\Message\Parser\Database;

use Rug\Message\Parser\AbstractParser;

abstract class AbstractDatabaseParser extends AbstractParser {

  private $_db;

  public function __construct($db) {
    $this->_db = $db;
  }

  /**
   * @return mixed
   */
  public function getDB() {
    return $this->_db;
  }


}
