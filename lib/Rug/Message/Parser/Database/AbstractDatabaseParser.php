<?php

namespace Rug\Message\Parser\Database;

use Rug\Coder\CoderManager;
use Rug\Message\Parser\AbstractParser;

abstract class AbstractDatabaseParser extends AbstractParser {

  /********************************************************************************************************************/

  private $_db;

  public function __construct(CoderManager $coder, $db) {
    parent::__construct($coder);
    $this->_db = $db;
  }

  /**
   * @return mixed
   */
  public function getDB() {
    return $this->_db;
  }

  /********************************************************************************************************************/

  protected function _error($data) {
    return sprintf("%s @ %s", parent::_error($data), $this->_db);
  }

}
