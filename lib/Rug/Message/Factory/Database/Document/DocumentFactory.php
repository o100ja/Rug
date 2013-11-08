<?php

namespace Rug\Message\Factory\Database\Document;

use Rug\Coder\CoderManager;
use Rug\Connector\Connector;
use Rug\Message\Factory\Database\DatabaseFactory;

class DocumentFactory extends DatabaseFactory {

  protected $_id;

  public function __construct(CoderManager $coder, Connector $connector, $db, $id) {
    parent::__construct($coder, $connector, $db);
    $this->_id = $id;
  }

  public function getID() {
    return $this->_id;
  }

  protected function _path() {
    return $this->_id;
  }

  public function path($path = '') {
    if (empty($path)) {
      return parent::path($this->_path());
    }
    return parent::path($this->_path() . '/' . $path);
  }

}
